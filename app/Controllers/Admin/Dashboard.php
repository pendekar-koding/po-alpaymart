<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\OrderModel;

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
            $data = [
                'total_products' => $productModel->where('user_id', $userId)->countAllResults(),
                'total_orders' => $orderModel->getOrderCountBySeller($userId),
                'recent_orders' => $orderModel->getOrdersBySeller($userId)
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