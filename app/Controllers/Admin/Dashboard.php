<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\SettingModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('user_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $productModel = new ProductModel();
        $orderModel = new OrderModel();
        
        $userId = session()->get('user_id');
        $role = session()->get('role');

        if ($role === 'seller') {
            // Seller hanya melihat data milik sendiri
            $orderItemModel = new \App\Models\OrderItemModel();
            $settingModel = new SettingModel();
            $donationAmount = (int) $settingModel->getSetting('donation_amount');
            
            $salesData = $orderItemModel->select('products.name as product_name, product_variants.variant_name, SUM(customer_order_items.quantity) as total_sold, SUM(customer_order_items.quantity * product_variants.price) as total_revenue')
                                       ->join('product_variants', 'product_variants.id = customer_order_items.product_variant_id')
                                       ->join('products', 'products.id = product_variants.product_id')
                                       ->where('products.user_id', $userId)
                                       ->groupBy('customer_order_items.product_variant_id')
                                       ->orderBy('total_sold', 'DESC')
                                       ->limit(10)
                                       ->findAll();
            
            // Calculate total sales revenue and quantity sold
            $totalSales = $orderItemModel->select('SUM(customer_order_items.quantity * product_variants.price) as total_sales, SUM(customer_order_items.quantity) as total_sold')
                                        ->join('product_variants', 'product_variants.id = customer_order_items.product_variant_id')
                                        ->join('products', 'products.id = product_variants.product_id')
                                        ->where('products.user_id', $userId)
                                        ->first();
            
            $data = [
                'total_products' => $productModel->where('user_id', $userId)->countAllResults(),
                'total_sold' => $totalSales['total_sold'] ?? 0,
                'total_sales' => $totalSales['total_sales'] ?? 0,
                'sales_data' => $salesData
            ];
        } else {
            // Admin melihat semua data
            $data = [
                'total_products' => $productModel->countAll(),
                'total_orders' => $orderModel->countAll(),
                'recent_orders' => $orderModel->getOrdersWithCustomer()
            ];
        }

        return view('admin/dashboard', $data);
    }
}