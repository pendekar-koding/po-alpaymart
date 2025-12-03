<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CatalogModel;
use App\Models\SettingModel;

class Home extends BaseController
{
    public function index()
    {
        $settingModel = new SettingModel();
        $websiteStatus = $settingModel->getSetting('website_status');
        
        if ($websiteStatus === 'closed') {
            $adminWhatsapp = $settingModel->getSetting('admin_whatsapp') ?? '6281234567890';
            return view('customer/maintenance', ['admin_whatsapp' => $adminWhatsapp]);
        }
        
        $productModel = new ProductModel();
        $catalogModel = new CatalogModel();
        
        $cart = session()->get('cart') ?? [];
        $cartCount = array_sum(array_column($cart, 'quantity'));
        
        $search = $this->request->getGet('search');
        
        if ($search) {
            $products = $productModel->searchProducts($search);
        } else {
            $products = $productModel->getActiveProducts();
        }
        
        $data = [
            'products' => $products,
            'catalogs' => $catalogModel->where('status', 'active')->findAll(),
            'cart_count' => $cartCount,
            'search' => $search
        ];

        return view('customer/index', $data);
    }
}