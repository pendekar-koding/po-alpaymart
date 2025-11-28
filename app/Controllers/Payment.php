<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\DivisionModel;

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
        $order = $this->orderModel->find($orderId);
        if (!$order) {
            return redirect()->to('/')->with('error', 'Pesanan tidak ditemukan');
        }

        // Get order items with product details
        $orderItems = $this->orderItemModel->select('customer_order_items.*, product_variants.variant_name, products.name as product_name')
                                          ->join('product_variants', 'product_variants.id = customer_order_items.product_variant_id')
                                          ->join('products', 'products.id = product_variants.product_id')
                                          ->where('order_id', $orderId)
                                          ->findAll();

        // Get division name
        $division = $this->divisionModel->find($order['division_id']);

        $data = [
            'order' => $order,
            'orderItems' => $orderItems,
            'division' => $division
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