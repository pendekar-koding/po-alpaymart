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
        $product = $this->select('products.*, users.shop_name')
                        ->join('users', 'products.user_id = users.id', 'left')
                        ->where('products.id', $id)
                        ->first();
        if ($product) {
            $variantModel = new ProductVariantModel();
            $product['variants'] = $variantModel->where('product_id', $id)->findAll();
        }
        return $product;
    }

    public function getActiveProducts()
    {
        $products = $this->select('products.*, users.shop_name, MIN(product_variants.price) as min_price, MAX(product_variants.price) as max_price, COUNT(product_variants.id) as variant_count')
                    ->join('users', 'products.user_id = users.id', 'left')
                    ->join('product_variants', 'products.id = product_variants.product_id AND product_variants.status = "active"', 'left')
                    ->where('products.status', 'active')
                    ->where('users.shop_status', 'open')
                    ->groupBy('products.id')
                    ->findAll();
        
        return $this->addDonationToProducts($products);
    }

    public function searchProducts($search)
    {
        $products = $this->select('products.*, users.shop_name, MIN(product_variants.price) as min_price, MAX(product_variants.price) as max_price, COUNT(product_variants.id) as variant_count')
                    ->join('users', 'products.user_id = users.id', 'left')
                    ->join('product_variants', 'products.id = product_variants.product_id AND product_variants.status = "active"', 'left')
                    ->where('products.status', 'active')
                    ->where('users.shop_status', 'open')
                    ->groupStart()
                        ->like('products.name', $search)
                        ->orLike('products.description', $search)
                        ->orLike('users.shop_name', $search)
                    ->groupEnd()
                    ->groupBy('products.id')
                    ->findAll();
        
        return $this->addDonationToProducts($products);
    }
    
    private function addDonationToProducts($products)
    {
        $settingModel = new SettingModel();
        $donationAmount = (int) $settingModel->getSetting('donation_amount');
        
        if ($donationAmount > 0) {
            foreach ($products as &$product) {
                if (isset($product['min_price'])) {
                    $product['min_price'] += $donationAmount;
                }
                if (isset($product['max_price'])) {
                    $product['max_price'] += $donationAmount;
                }
            }
        }
        
        return $products;
    }
}