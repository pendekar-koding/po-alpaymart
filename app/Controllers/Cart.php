<?php

namespace App\Controllers;

use App\Models\ProductVariantModel;

class Cart extends BaseController
{
    public function add()
    {
        $variantId = $this->request->getPost('variant_id');
        $quantity = $this->request->getPost('quantity') ?? 1;
        
        $variantModel = new ProductVariantModel();
        $variant = $variantModel->select('product_variants.*, products.name as product_name')
                                ->join('products', 'products.id = product_variants.product_id')
                                ->where('product_variants.id', $variantId)
                                ->first();
        
        if (!$variant || $variant['stock'] < $quantity) {
            return $this->response->setJSON(['success' => false, 'message' => 'Stok tidak mencukupi']);
        }
        
        $cart = session()->get('cart') ?? [];
        
        if (isset($cart[$variantId])) {
            $cart[$variantId]['quantity'] += $quantity;
        } else {
            $cart[$variantId] = [
                'variant_id' => $variantId,
                'product_name' => $variant['product_name'],
                'variant_name' => $variant['variant_name'],
                'price' => $variant['price'],
                'quantity' => $quantity
            ];
        }
        
        session()->set('cart', $cart);
        
        return $this->response->setJSON(['success' => true, 'message' => 'Produk ditambahkan ke keranjang']);
    }
    
    public function index()
    {
        $cart = session()->get('cart') ?? [];
        
        // Enrich cart data with product names
        $variantModel = new ProductVariantModel();
        foreach ($cart as $key => $item) {
            if (!isset($item['product_name'])) {
                $variant = $variantModel->select('product_variants.*, products.name as product_name')
                                        ->join('products', 'products.id = product_variants.product_id')
                                        ->where('product_variants.id', $item['variant_id'])
                                        ->first();
                if ($variant) {
                    $cart[$key]['product_name'] = $variant['product_name'];
                }
            }
        }
        
        // Update session with enriched data
        session()->set('cart', $cart);
        
        $data = ['cart' => $cart];
        return view('shop/cart', $data);
    }
    
    public function remove()
    {
        $variantId = $this->request->getPost('variant_id');
        
        $cart = session()->get('cart') ?? [];
        
        if (isset($cart[$variantId])) {
            unset($cart[$variantId]);
            session()->set('cart', $cart);
            return $this->response->setJSON(['success' => true, 'message' => 'Produk berhasil dihapus dari keranjang']);
        }
        
        return $this->response->setJSON(['success' => false, 'message' => 'Produk tidak ditemukan']);
    }
}