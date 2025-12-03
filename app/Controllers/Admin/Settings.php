<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class Settings extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'title' => 'Pengaturan',
            'settings' => $this->settingModel->getAllSettings()
        ];

        return view('admin/settings/index', $data);
    }

    public function update()
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $websiteStatus = $this->request->getPost('website_status');
        $donationAmount = $this->request->getPost('donation_amount');
        $donationDescription = $this->request->getPost('donation_description');
        $adminWhatsapp = $this->request->getPost('admin_whatsapp');

        $this->settingModel->setSetting('website_status', $websiteStatus);
        $this->settingModel->setSetting('donation_amount', $donationAmount);
        $this->settingModel->setSetting('donation_description', $donationDescription);
        $this->settingModel->setSetting('admin_whatsapp', $adminWhatsapp);

        session()->setFlashdata('success', 'Pengaturan berhasil disimpan');
        return redirect()->to('/admin/settings');
    }
}