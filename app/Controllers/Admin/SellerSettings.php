<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class SellerSettings extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'seller') {
            return redirect()->to('/admin/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        $data = [
            'title' => 'Pengaturan Toko',
            'user' => $user
        ];

        return view('admin/seller-settings/index', $data);
    }

    public function update()
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'seller') {
            return redirect()->to('/admin/login');
        }

        $userId = session()->get('user_id');
        $shopStatus = $this->request->getPost('shop_status');

        $this->userModel->update($userId, ['shop_status' => $shopStatus]);

        session()->setFlashdata('success', 'Status toko berhasil diupdate');
        return redirect()->to('/admin/seller-settings');
    }
}