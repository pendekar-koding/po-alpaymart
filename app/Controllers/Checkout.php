<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DivisionModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\SettingModel;

class Checkout extends BaseController
{
    protected $divisionModel;
    protected $orderModel;
    protected $orderItemModel;

    public function __construct()
    {
        $this->divisionModel = new DivisionModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
    }

    public function index()
    {
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to('/cart')->with('error', 'Keranjang kosong');
        }

        // Enrich cart data with product names if missing
        $variantModel = new \App\Models\ProductVariantModel();
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
        session()->set('cart', $cart);

        $data['cart'] = $cart;
        $data['divisions'] = $this->divisionModel->findAll();
        
        $total = 0;
        $totalDonation = 0;
        $settingModel = new SettingModel();
        $donationAmount = (int) $settingModel->getSetting('donation_amount');
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
            $totalDonation += $donationAmount * $item['quantity'];
        }
        
        $data['total'] = $total;
        $data['total_donation'] = $totalDonation;
        $data['donation_description'] = $settingModel->getSetting('donation_description');

        return view('shop/checkout', $data);
    }

    public function process()
    {
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to('/cart')->with('error', 'Keranjang kosong');
        }
        
        // Check if any shop is closed
        $variantModel = new \App\Models\ProductVariantModel();
        $userModel = new \App\Models\UserModel();
        $closedShops = [];
        
        foreach ($cart as $key => $item) {
            $variant = $variantModel->select('product_variants.*, products.name as product_name, products.user_id')
                                   ->join('products', 'products.id = product_variants.product_id')
                                   ->where('product_variants.id', $item['variant_id'])
                                   ->first();
            if ($variant) {
                $user = $userModel->find($variant['user_id']);
                if ($user && $user['shop_status'] === 'closed') {
                    $closedShops[] = $user['shop_name'];
                    unset($cart[$key]);
                }
            }
        }
        
        if (!empty($closedShops)) {
            session()->set('cart', $cart);
            $message = 'Toko berikut sedang tutup dan produknya telah dihapus dari keranjang: ' . implode(', ', array_unique($closedShops));
            return redirect()->to('/cart')->with('error', $message);
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama' => 'required|min_length[3]',
            'whatsapp' => 'required|min_length[10]',
            'division_id' => 'required|integer',
            'payment_method' => 'required|in_list[QRIS,COD]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Generate order number
        $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Calculate total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        // Create order
        $orderId = $this->orderModel->insert([
            'order_number' => $orderNumber,
            'customer_name' => $this->request->getPost('nama'),
            'customer_whatsapp' => $this->request->getPost('whatsapp'),
            'division_id' => $this->request->getPost('division_id'),
            'payment_method' => $this->request->getPost('payment_method'),
            'total_amount' => $total,
            'status' => 'pending'
        ]);
        
        // Create order items
        foreach ($cart as $item) {
            $this->orderItemModel->insert([
                'order_id' => $orderId,
                'product_variant_id' => $item['variant_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity']
            ]);
        }
        
        // Clear cart after successful checkout
        session()->remove('cart');
        
        // Redirect to payment page based on payment method
        return redirect()->to('/payment/' . $orderId);
    }
}