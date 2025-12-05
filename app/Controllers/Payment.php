<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\DivisionModel;
use App\Models\SettingModel;

class Payment extends BaseController
{
    protected $orderModel;
    protected $orderItemModel;
    protected $divisionModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->divisionModel = new DivisionModel();
    }

    public function index($orderId)
    {
        // Check if online pre-order is closed
        $settingModel = new SettingModel();
        $poOnlineStatus = $settingModel->getSetting('website_status');
        if ($poOnlineStatus === 'closed') {
            return redirect()->to('/')->with('error', 'Maaf, PO Online sedang ditutup oleh admin');
        }

        $order = $this->orderModel->find($orderId);
        if (!$order) {
            return redirect()->to('/')->with('error', 'Pesanan tidak ditemukan');
        }

        // Check if any shop in the order is closed
        $orderItems = $this->orderItemModel->select('customer_order_items.*, product_variants.variant_name, products.name as product_name, products.user_id')
                                          ->join('product_variants', 'product_variants.id = customer_order_items.product_variant_id')
                                          ->join('products', 'products.id = product_variants.product_id')
                                          ->where('order_id', $orderId)
                                          ->findAll();

        $userModel = new \App\Models\UserModel();
        foreach ($orderItems as $item) {
            $user = $userModel->find($item['user_id']);
            if ($user && $user['shop_status'] === 'closed') {
                return redirect()->to('/')->with('error', 'Maaf, toko ' . $user['shop_name'] . ' sedang tutup. Pesanan tidak dapat diproses.');
            }
        }

        // Get order items with product details (already fetched above for validation)
        $orderItems = $this->orderItemModel->select('customer_order_items.*, product_variants.variant_name, products.name as product_name')
                                          ->join('product_variants', 'product_variants.id = customer_order_items.product_variant_id')
                                          ->join('products', 'products.id = product_variants.product_id')
                                          ->where('order_id', $orderId)
                                          ->findAll();

        // Get division name
        $division = $this->divisionModel->find($order['division_id']);
        
        // Calculate donation info
        $donationAmount = (int) $settingModel->getSetting('donation_amount');
        $totalDonation = 0;
        foreach ($orderItems as $item) {
            $totalDonation += $donationAmount * $item['quantity'];
        }

        $data = [
            'order' => $order,
            'orderItems' => $orderItems,
            'division' => $division,
            'total_donation' => $totalDonation,
            'donation_description' => $settingModel->getSetting('donation_description')
        ];

        if ($order['payment_method'] === 'QRIS') {
            return view('payment/qris', $data);
        } else {
            return view('payment/cod', $data);
        }
    }

    public function downloadQris()
    {
        $qrisPath = WRITEPATH . 'qris/alpaymart.jpg';
        if (file_exists($qrisPath)) {
            return $this->response->download($qrisPath, null);
        }
        return redirect()->back()->with('error', 'File QRIS tidak ditemukan');
    }
}