<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DivisionModel;

class Divisions extends BaseController
{
    protected $divisionModel;

    public function __construct()
    {
        $this->divisionModel = new DivisionModel();
    }

    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/admin/products')->with('error', 'Akses ditolak');
        }

        $data['divisions'] = $this->divisionModel->findAll();
        return view('admin/divisions/index', $data);
    }

    public function create()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/admin/products')->with('error', 'Akses ditolak');
        }

        return view('admin/divisions/create');
    }

    public function store()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/admin/products')->with('error', 'Akses ditolak');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_divisi' => 'required|min_length[3]|max_length[255]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $this->divisionModel->save([
            'nama_divisi' => strtoupper($this->request->getPost('nama_divisi'))
        ]);

        return redirect()->to('/admin/divisions')->with('success', 'Divisi berhasil ditambahkan');
    }

    public function edit($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/admin/products')->with('error', 'Akses ditolak');
        }

        $data['division'] = $this->divisionModel->find($id);
        if (!$data['division']) {
            return redirect()->to('/admin/divisions')->with('error', 'Divisi tidak ditemukan');
        }

        return view('admin/divisions/edit', $data);
    }

    public function update($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/admin/products')->with('error', 'Akses ditolak');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_divisi' => 'required|min_length[3]|max_length[255]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $this->divisionModel->update($id, [
            'nama_divisi' => strtoupper($this->request->getPost('nama_divisi'))
        ]);

        return redirect()->to('/admin/divisions')->with('success', 'Divisi berhasil diperbarui');
    }

    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/admin/products')->with('error', 'Akses ditolak');
        }

        $this->divisionModel->delete($id);
        return redirect()->to('/admin/divisions')->with('success', 'Divisi berhasil dihapus');
    }
}