<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = ['customer_id', 'total_amount', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getOrdersWithCustomer()
    {
        return $this->select('orders.*, customers.name as customer_name')
                    ->join('customers', 'customers.id = orders.customer_id', 'left')
                    ->orderBy('orders.created_at', 'DESC')
                    ->findAll();
    }

    public function getOrderCountBySeller($userId)
    {
        return $this->select('COUNT(DISTINCT orders.id) as total')
                    ->join('order_items', 'orders.id = order_items.order_id')
                    ->join('products', 'order_items.product_id = products.id')
                    ->where('products.user_id', $userId)
                    ->get()->getRow()->total;
    }

    public function getOrdersBySeller($userId)
    {
        return $this->select('orders.*, customers.name as customer_name')
                    ->join('customers', 'customers.id = orders.customer_id', 'left')
                    ->join('order_items', 'orders.id = order_items.order_id')
                    ->join('products', 'order_items.product_id = products.id')
                    ->where('products.user_id', $userId)
                    ->groupBy('orders.id')
                    ->orderBy('orders.created_at', 'DESC')
                    ->findAll();
    }
}