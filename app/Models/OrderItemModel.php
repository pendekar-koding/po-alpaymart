<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table = 'customer_order_items';
    protected $primaryKey = 'id';
    protected $allowedFields = ['order_id', 'product_variant_id', 'quantity', 'price', 'subtotal'];
}