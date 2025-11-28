<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;

class Orders extends BaseController
{
    protected $orderModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
    }

    public function index()
    {
        if (!session()->get('user_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $userId = session()->get('user_id');
        $role = session()->get('role');

        if ($role === 'seller') {
            $orders = $this->orderModel->getOrdersBySeller($userId);
        } else {
            $orders = $this->orderModel->getOrdersWithCustomer();
        }

        $data = [
            'orders' => $orders,
            'title' => 'Pesanan'
        ];

        return view('admin/orders/index', $data);
    }

    public function view($id)
    {
        if (!session()->get('user_logged_in')) {
            return redirect()->to('/admin/login');
        }

        // Check if seller can only view orders containing their products
        if (session()->get('role') === 'seller') {
            $hasAccess = $this->orderModel->select('orders.id')
                              ->join('order_items', 'orders.id = order_items.order_id')
                              ->join('products', 'order_items.product_id = products.id')
                              ->where('orders.id', $id)
                              ->where('products.user_id', session()->get('user_id'))
                              ->first();
            
            if (!$hasAccess) {
                return redirect()->to('/admin/orders')->with('error', 'Akses ditolak');
            }
        }

        $db = \Config\Database::connect();
        $order = $db->table('orders')
                   ->select('orders.*, customers.name as customer_name, customers.phone, customers.address')
                   ->join('customers', 'customers.id = orders.customer_id')
                   ->where('orders.id', $id)
                   ->get()->getRow();

        $orderItems = $db->table('order_items')
                        ->select('order_items.*, products.name as product_name')
                        ->join('products', 'products.id = order_items.product_id')
                        ->where('order_id', $id)
                        ->get()->getResult();

        $data = [
            'order' => $order,
            'order_items' => $orderItems
        ];

        return view('admin/orders/view', $data);
    }

    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        $this->orderModel->update($id, ['status' => $status]);
        
        return redirect()->back()->with('success', 'Status order berhasil diupdate');
    }
}