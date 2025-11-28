<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['user_id', 'name', 'description', 'image', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getProductsWithVariants($userId = null)
    {
        $query = $this->select('products.*, users.shop_name, COUNT(product_variants.id) as variant_count')
                      ->join('users', 'products.user_id = users.id', 'left')
                      ->join('product_variants', 'products.id = product_variants.product_id', 'left')
                      ->groupBy('products.id');
        
        if ($userId) {
            $query->where('products.user_id', $userId);
        }
        
        return $query->findAll();
    }

    public function getProductWithVariants($id)
    {
        $product = $this->find($id);
        if ($product) {
            $variantModel = new ProductVariantModel();
            $product['variants'] = $variantModel->where('product_id', $id)->findAll();
        }
        return $product;
    }

    public function getActiveProducts()
    {
        return $this->select('products.*, users.shop_name, MIN(product_variants.price) as min_price, MAX(product_variants.price) as max_price, COUNT(product_variants.id) as variant_count')
                    ->join('users', 'products.user_id = users.id', 'left')
                    ->join('product_variants', 'products.id = product_variants.product_id AND product_variants.status = "active"', 'left')
                    ->where('products.status', 'active')
                    ->groupBy('products.id')
                    ->findAll();
    }

    public function searchProducts($search)
    {
        return $this->select('products.*, users.shop_name, MIN(product_variants.price) as min_price, MAX(product_variants.price) as max_price, COUNT(product_variants.id) as variant_count')
                    ->join('users', 'products.user_id = users.id', 'left')
                    ->join('product_variants', 'products.id = product_variants.product_id AND product_variants.status = "active"', 'left')
                    ->where('products.status', 'active')
                    ->groupStart()
                        ->like('products.name', $search)
                        ->orLike('products.description', $search)
                        ->orLike('users.shop_name', $search)
                    ->groupEnd()
                    ->groupBy('products.id')
                    ->findAll();
    }
}