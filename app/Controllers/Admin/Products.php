<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\ProductVariantModel;

class Products extends BaseController
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
        if (!session()->get('user_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $userId = session()->get('role') === 'seller' ? session()->get('user_id') : null;
        
        $data = [
            'products' => $this->productModel->getProductsWithVariants($userId),
            'is_admin' => session()->get('role') === 'admin'
        ];

        $data['title'] = 'Produk';
        return view('admin/products/index', $data);
    }

    public function create()
    {
        if (!session()->get('user_logged_in')) {
            return redirect()->to('/admin/login');
        }

        if (session()->get('role') === 'admin') {
            return redirect()->to('/admin/products')->with('error', 'Admin tidak dapat membuat produk');
        }

        $data['title'] = 'Tambah Produk';
        return view('admin/products/create', $data);
    }

    public function store()
    {
        if (session()->get('role') === 'admin') {
            return redirect()->to('/admin/products')->with('error', 'Admin tidak dapat membuat produk');
        }

        $productData = [
            'user_id' => session()->get('user_id'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'status' => $this->request->getPost('status')
        ];

        $productId = $this->productModel->insert($productData);
        
        // Save variants
        $variants = $this->request->getPost('variants');
        if ($variants) {
            foreach ($variants as $variant) {
                if (!empty($variant['variant_name']) && !empty($variant['price'])) {
                    $variantData = [
                        'product_id' => $productId,
                        'variant_name' => $variant['variant_name'],
                        'price' => $variant['price'],
                        'stock' => $variant['stock'] ?? 0,
                        'sku' => $variant['sku'] ?? '',
                        'status' => 'active'
                    ];
                    $this->variantModel->insert($variantData);
                }
            }
        }

        return redirect()->to('/admin/products')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        if (!session()->get('user_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $product = $this->productModel->getProductWithVariants($id);
        
        // Check if seller can only edit their own products
        if (session()->get('role') === 'seller' && $product['user_id'] != session()->get('user_id')) {
            return redirect()->to('/admin/products')->with('error', 'Akses ditolak');
        }

        $data = [
            'product' => $product
        ];

        return view('admin/products/edit', $data);
    }

    public function update($id)
    {
        $productData = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'status' => $this->request->getPost('status')
        ];

        $this->productModel->update($id, $productData);
        
        // Delete existing variants
        $this->variantModel->where('product_id', $id)->delete();
        
        // Save new variants
        $variants = $this->request->getPost('variants');
        if ($variants) {
            foreach ($variants as $variant) {
                if (!empty($variant['variant_name']) && !empty($variant['price'])) {
                    $variantData = [
                        'product_id' => $id,
                        'variant_name' => $variant['variant_name'],
                        'price' => $variant['price'],
                        'stock' => $variant['stock'] ?? 0,
                        'sku' => $variant['sku'] ?? '',
                        'status' => 'active'
                    ];
                    $this->variantModel->insert($variantData);
                }
            }
        }

        return redirect()->to('/admin/products')->with('success', 'Produk berhasil diupdate');
    }

    public function delete($id)
    {
        $product = $this->productModel->find($id);
        
        // Check if seller can only delete their own products
        if (session()->get('role') === 'seller' && $product['user_id'] != session()->get('user_id')) {
            return redirect()->to('/admin/products')->with('error', 'Akses ditolak');
        }
        
        // Delete variants first
        $this->variantModel->where('product_id', $id)->delete();
        // Delete product
        $this->productModel->delete($id);
        return redirect()->to('/admin/products')->with('success', 'Produk berhasil dihapus');
    }
}