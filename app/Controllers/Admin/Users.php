<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'title' => 'Manajemen User',
            'users' => $this->userModel->findAll()
        ];

        return view('admin/users/index', $data);
    }

    public function create()
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data['title'] = 'Tambah User';
        return view('admin/users/create', $data);
    }

    public function store()
    {
        $data = [
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'full_name' => $this->request->getPost('full_name'),
            'shop_name' => $this->request->getPost('shop_name'),
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->userModel->save($data)) {
            return redirect()->to('/admin/users')->with('success', 'User berhasil ditambahkan');
        }
        return redirect()->back()->with('error', 'Gagal menambahkan user');
    }

    public function edit($id)
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'title' => 'Edit User',
            'user' => $this->userModel->find($id)
        ];

        return view('admin/users/edit', $data);
    }

    public function update($id)
    {
        $data = [
            'username' => $this->request->getPost('username'),
            'full_name' => $this->request->getPost('full_name'),
            'shop_name' => $this->request->getPost('shop_name'),
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status'),
            'shop_status' => $this->request->getPost('shop_status')
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = $password;
        }

        if ($this->userModel->update($id, $data)) {
            return redirect()->to('/admin/users')->with('success', 'User berhasil diupdate');
        }
        return redirect()->back()->with('error', 'Gagal mengupdate user');
    }

    public function delete($id)
    {
        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/users')->with('success', 'User berhasil dihapus');
        }
        return redirect()->back()->with('error', 'Gagal menghapus user');
    }
    
    public function toggleShopStatus()
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        
        $userId = $this->request->getPost('user_id');
        $shopStatus = $this->request->getPost('shop_status');
        
        if ($this->userModel->update($userId, ['shop_status' => $shopStatus])) {
            return $this->response->setJSON(['success' => true]);
        }
        
        return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengubah status']);
    }
}