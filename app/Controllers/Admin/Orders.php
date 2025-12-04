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
        $search = $this->request->getGet('search');

        if ($role === 'seller') {
            $orders = $this->orderModel->getOrdersBySeller($userId, $search);
        } else {
            $orders = $this->orderModel->getOrdersWithCustomer($search);
        }

        $data = [
            'orders' => $orders,
            'search' => $search,
            'title' => 'Pesanan'
        ];

        return view('admin/orders/index', $data);
    }

    public function view($id)
    {
        if (!session()->get('user_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $order = $this->orderModel->select('customer_orders.*, divisions.nama_divisi')
                                  ->join('divisions', 'divisions.id = customer_orders.division_id')
                                  ->where('customer_orders.id', $id)
                                  ->first();

        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        $db = \Config\Database::connect();
        $orderItems = $db->table('customer_order_items')
                        ->select('customer_order_items.*, product_variants.variant_name, products.name as product_name, users.shop_name')
                        ->join('product_variants', 'product_variants.id = customer_order_items.product_variant_id')
                        ->join('products', 'products.id = product_variants.product_id')
                        ->join('users', 'users.id = products.user_id')
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

    public function delete($id)
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/admin/orders')->with('error', 'Akses ditolak');
        }

        $db = \Config\Database::connect();
        $db->table('customer_order_items')->where('order_id', $id)->delete();
        $this->orderModel->delete($id);
        
        return redirect()->to('/admin/orders')->with('success', 'Pesanan berhasil dihapus');
    }

    public function deleteAll()
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/admin/orders')->with('error', 'Akses ditolak');
        }

        $db = \Config\Database::connect();
        $db->table('customer_order_items')->truncate();
        $db->table('customer_orders')->truncate();
        
        return redirect()->to('/admin/orders')->with('success', 'Semua pesanan berhasil dihapus');
    }
}