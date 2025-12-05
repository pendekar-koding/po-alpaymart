<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'customer_orders';
    protected $primaryKey = 'id';
    protected $allowedFields = ['order_number', 'customer_name', 'customer_whatsapp', 'division_id', 'payment_method', 'total_amount', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getOrdersWithDivision()
    {
        return $this->select('customer_orders.*, divisions.nama_divisi')
                    ->join('divisions', 'divisions.id = customer_orders.division_id')
                    ->orderBy('customer_orders.created_at', 'DESC')
                    ->findAll();
    }

    public function getOrdersWithCustomer($search = null)
    {
        $query = $this->select('customer_orders.*, divisions.nama_divisi')
                      ->join('divisions', 'divisions.id = customer_orders.division_id');
        
        if ($search) {
            $query->groupStart()
                  ->like('customer_orders.order_number', $search)
                  ->orLike('customer_orders.customer_name', $search)
                  ->orLike('customer_orders.customer_whatsapp', $search)
                  ->groupEnd();
        }
        
        return $query->orderBy('customer_orders.created_at', 'DESC')->findAll();
    }

    public function getOrdersWithCustomerOrderbyASC($search = null)
    {
        $query = $this->select('customer_orders.*, divisions.nama_divisi')
                      ->join('divisions', 'divisions.id = customer_orders.division_id');

        if ($search) {
            $query->groupStart()
                  ->like('customer_orders.order_number', $search)
                  ->orLike('customer_orders.customer_name', $search)
                  ->orLike('customer_orders.customer_whatsapp', $search)
                  ->groupEnd();
        }

        return $query->orderBy('customer_orders.customer_name', 'ASC')->findAll();
    }

    public function getOrderCountBySeller($userId)
    {
        return $this->join('customer_order_items', 'customer_order_items.order_id = customer_orders.id')
                    ->join('product_variants', 'product_variants.id = customer_order_items.product_variant_id')
                    ->join('products', 'products.id = product_variants.product_id')
                    ->where('products.user_id', $userId)
                    ->countAllResults();
    }

    public function getOrdersBySeller($userId, $search = null)
    {
        $query = $this->select('customer_orders.*, divisions.nama_divisi')
                      ->join('divisions', 'divisions.id = customer_orders.division_id')
                      ->join('customer_order_items', 'customer_order_items.order_id = customer_orders.id')
                      ->join('product_variants', 'product_variants.id = customer_order_items.product_variant_id')
                      ->join('products', 'products.id = product_variants.product_id')
                      ->where('products.user_id', $userId);
        
        if ($search) {
            $query->groupStart()
                  ->like('customer_orders.order_number', $search)
                  ->orLike('customer_orders.customer_name', $search)
                  ->orLike('customer_orders.customer_whatsapp', $search)
                  ->groupEnd();
        }
        
        return $query->groupBy('customer_orders.id')
                     ->orderBy('customer_orders.created_at', 'DESC')
                     ->findAll();
    }

    public function getOrdersWithCustomerASC($search = null)
    {
        $query = $this->select('customer_orders.*, divisions.nama_divisi')
                      ->join('divisions', 'divisions.id = customer_orders.division_id');
        
        if ($search) {
            $query->groupStart()
                  ->like('customer_orders.order_number', $search)
                  ->orLike('customer_orders.customer_name', $search)
                  ->orLike('customer_orders.customer_whatsapp', $search)
                  ->groupEnd();
        }
        
        return $query->orderBy('customer_orders.created_at', 'ASC')->findAll();
    }
}