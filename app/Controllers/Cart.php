<?php

namespace App\Controllers;

use App\Models\ProductVariantModel;
use App\Models\SettingModel;

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
        
        // Reduce stock
        $variantModel->update($variantId, ['stock' => $variant['stock'] - $quantity]);
        
        // Add donation to price
        $settingModel = new SettingModel();
        $donationAmount = (int) $settingModel->getSetting('donation_amount');
        $finalPrice = $variant['price'] + $donationAmount;
        
        $cart = session()->get('cart') ?? [];
        
        if (isset($cart[$variantId])) {
            $cart[$variantId]['quantity'] += $quantity;
        } else {
            $cart[$variantId] = [
                'variant_id' => $variantId,
                'product_name' => $variant['product_name'],
                'variant_name' => $variant['variant_name'],
                'price' => $finalPrice,
                'quantity' => $quantity,
                'note' => ''
            ];
        }
        
        session()->set('cart', $cart);
        
        return $this->response->setJSON(['success' => true, 'message' => 'Produk ditambahkan ke keranjang']);
    }
    
    public function index()
    {
        $cart = session()->get('cart') ?? [];
        
        // Enrich cart data and ensure prices include donation
        $variantModel = new ProductVariantModel();
        $settingModel = new SettingModel();
        $donationAmount = (int) $settingModel->getSetting('donation_amount');
        
        foreach ($cart as $key => $item) {
            if (!isset($item['product_name'])) {
                $variant = $variantModel->select('product_variants.*, products.name as product_name')
                                        ->join('products', 'products.id = product_variants.product_id')
                                        ->where('product_variants.id', $item['variant_id'])
                                        ->first();
                if ($variant) {
                    $cart[$key]['product_name'] = $variant['product_name'];
                    $cart[$key]['price'] = $variant['price'] + $donationAmount;
                    if (!isset($cart[$key]['note'])) {
                        $cart[$key]['note'] = '';
                    }
                }
            }
        }
        
        // Update session with enriched data
        session()->set('cart', $cart);
        
        $data = [
            'cart' => $cart,
            'donation_amount' => $donationAmount,
            'donation_description' => $settingModel->getSetting('donation_description')
        ];
        return view('shop/cart', $data);
    }
    
    public function remove()
    {
        $variantId = $this->request->getPost('variant_id');
        
        $cart = session()->get('cart') ?? [];
        
        if (isset($cart[$variantId])) {
            // Return stock before removing from cart
            $variantModel = new ProductVariantModel();
            $variant = $variantModel->find($variantId);
            if ($variant) {
                $variantModel->update($variantId, ['stock' => $variant['stock'] + $cart[$variantId]['quantity']]);
            }
            
            unset($cart[$variantId]);
            session()->set('cart', $cart);
            return $this->response->setJSON(['success' => true, 'message' => 'Produk berhasil dihapus dari keranjang']);
        }
        
        return $this->response->setJSON(['success' => false, 'message' => 'Produk tidak ditemukan']);
    }
    
    public function update()
    {
        $variantId = $this->request->getPost('variant_id');
        $newQuantity = $this->request->getPost('quantity');
        
        $cart = session()->get('cart') ?? [];
        
        if (!isset($cart[$variantId])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Produk tidak ditemukan']);
        }
        
        $variantModel = new ProductVariantModel();
        $variant = $variantModel->find($variantId);
        
        if (!$variant) {
            return $this->response->setJSON(['success' => false, 'message' => 'Produk tidak ditemukan']);
        }
        
        $currentQuantity = $cart[$variantId]['quantity'];
        $quantityDiff = $newQuantity - $currentQuantity;
        
        // Check if we have enough stock for increase
        if ($quantityDiff > 0 && $variant['stock'] < $quantityDiff) {
            return $this->response->setJSON(['success' => false, 'message' => 'Stok tidak mencukupi']);
        }
        
        // Update stock
        $variantModel->update($variantId, ['stock' => $variant['stock'] - $quantityDiff]);
        
        // Update cart
        $cart[$variantId]['quantity'] = $newQuantity;
        session()->set('cart', $cart);
        
        return $this->response->setJSON(['success' => true, 'message' => 'Keranjang berhasil diperbarui']);
    }
    
    public function updateNote()
    {
        $variantId = $this->request->getPost('variant_id');
        $note = $this->request->getPost('note') ?? '';
        
        $cart = session()->get('cart') ?? [];
        
        if (isset($cart[$variantId])) {
            $cart[$variantId]['note'] = $note;
            session()->set('cart', $cart);
            return $this->response->setJSON(['success' => true, 'message' => 'Catatan berhasil diperbarui']);
        }
        
        return $this->response->setJSON(['success' => false, 'message' => 'Produk tidak ditemukan']);
    }
}