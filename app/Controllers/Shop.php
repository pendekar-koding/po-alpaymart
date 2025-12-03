<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ProductVariantModel;
use App\Models\SettingModel;

class Shop extends BaseController
{
    protected $productModel;
    protected $variantModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->variantModel = new ProductVariantModel();
    }

    public function index()
    {
        $data = [
            'products' => $this->productModel->getActiveProducts()
        ];

        return view('shop/index', $data);
    }

    public function product($id)
    {
        $product = $this->productModel->select('products.*, users.shop_name')
                                     ->join('users', 'products.user_id = users.id', 'left')
                                     ->where('products.id', $id)
                                     ->where('products.status', 'active')
                                     ->first();

        if (!$product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Produk tidak ditemukan');
        }

        $variants = $this->variantModel->where('product_id', $id)
                                      ->where('status', 'active')
                                      ->findAll();
        
        // Add donation to variant prices
        $settingModel = new SettingModel();
        $donationAmount = (int) $settingModel->getSetting('donation_amount');
        if ($donationAmount > 0) {
            foreach ($variants as &$variant) {
                $variant['price'] += $donationAmount;
            }
        }

        $cart = session()->get('cart') ?? [];
        $cartCount = array_sum(array_column($cart, 'quantity'));

        $data = [
            'product' => $product,
            'variants' => $variants,
            'cart_count' => $cartCount
        ];

        return view('shop/product', $data);
    }
}