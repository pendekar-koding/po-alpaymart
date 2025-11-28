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
        $variant = $variantModel->find($variantId);
        
        if (!$variant || $variant['stock'] < $quantity) {
            return $this->response->setJSON(['success' => false, 'message' => 'Stok tidak mencukupi']);
        }
        
        $cart = session()->get('cart') ?? [];
        
        if (isset($cart[$variantId])) {
            $cart[$variantId]['quantity'] += $quantity;
        } else {
            $cart[$variantId] = [
                'variant_id' => $variantId,
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
        $data = ['cart' => $cart];
        return view('shop/cart', $data);
    }
}