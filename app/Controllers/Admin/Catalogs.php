<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CatalogModel;

class Catalogs extends BaseController
{
    protected $catalogModel;

    public function __construct()
    {
        $this->catalogModel = new CatalogModel();
    }

    public function index()
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'title' => 'Katalog',
            'catalogs' => $this->catalogModel->findAll()
        ];

        return view('admin/catalogs/index', $data);
    }

    public function create()
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data['title'] = 'Tambah Katalog';
        return view('admin/catalogs/create', $data);
    }

    public function store()
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $file = $this->request->getFile('catalog_file');
        
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return redirect()->back()->with('error', 'File tidak valid atau tidak ditemukan');
        }

        // Validasi berdasarkan ekstensi file
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $fileExtension = strtolower($file->getClientExtension());
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            return redirect()->back()->with('error', 'File harus berupa gambar (JPG, PNG) atau PDF');
        }

        // Validasi ukuran file (max 10MB)
        if ($file->getSize() > 10 * 1024 * 1024) {
            return redirect()->back()->with('error', 'Ukuran file maksimal 10MB');
        }

        // Hapus semua katalog lama
        $oldCatalogs = $this->catalogModel->findAll();
        foreach ($oldCatalogs as $oldCatalog) {
            if (file_exists(WRITEPATH . 'uploads/catalogs/' . $oldCatalog['file_name'])) {
                unlink(WRITEPATH . 'uploads/catalogs/' . $oldCatalog['file_name']);
            }
        }
        $this->catalogModel->truncate();

        $fileName = $file->getRandomName();
        
        try {
            $file->move(WRITEPATH . 'uploads/catalogs/', $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupload file: ' . $e->getMessage());
        }

        // Tentukan MIME type berdasarkan ekstensi
        $mimeType = match($fileExtension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'pdf' => 'application/pdf',
            default => 'application/octet-stream'
        };

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'file_name' => $fileName,
            'file_type' => $mimeType,
            'file_size' => $file->getSize(),
            'status' => $this->request->getPost('status')
        ];

        if ($this->catalogModel->save($data)) {
            return redirect()->to('/admin/catalogs')->with('success', 'Katalog berhasil ditambahkan');
        }
        return redirect()->back()->with('error', 'Gagal menambahkan katalog');
    }

    public function edit($id)
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'title' => 'Edit Katalog',
            'catalog' => $this->catalogModel->find($id)
        ];

        return view('admin/catalogs/edit', $data);
    }

    public function update($id)
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'status' => $this->request->getPost('status')
        ];

        $file = $this->request->getFile('catalog_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
            $fileExtension = strtolower($file->getClientExtension());
            
            if (in_array($fileExtension, $allowedExtensions)) {
                // Delete old file
                $oldCatalog = $this->catalogModel->find($id);
                if ($oldCatalog && file_exists(WRITEPATH . 'uploads/catalogs/' . $oldCatalog['file_name'])) {
                    unlink(WRITEPATH . 'uploads/catalogs/' . $oldCatalog['file_name']);
                }

                $fileName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads/catalogs/', $fileName);
                
                $mimeType = match($fileExtension) {
                    'jpg', 'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    'pdf' => 'application/pdf',
                    default => 'application/octet-stream'
                };
                
                $data['file_name'] = $fileName;
                $data['file_type'] = $mimeType;
                $data['file_size'] = $file->getSize();
            }
        }

        if ($this->catalogModel->update($id, $data)) {
            return redirect()->to('/admin/catalogs')->with('success', 'Katalog berhasil diupdate');
        }
        return redirect()->back()->with('error', 'Gagal mengupdate katalog');
    }

    public function delete($id)
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $catalog = $this->catalogModel->find($id);
        if ($catalog && file_exists(WRITEPATH . 'uploads/catalogs/' . $catalog['file_name'])) {
            unlink(WRITEPATH . 'uploads/catalogs/' . $catalog['file_name']);
        }

        if ($this->catalogModel->delete($id)) {
            return redirect()->to('/admin/catalogs')->with('success', 'Katalog berhasil dihapus');
        }
        return redirect()->back()->with('error', 'Gagal menghapus katalog');
    }

    public function download($id)
    {
        $catalog = $this->catalogModel->find($id);
        if (!$catalog) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Katalog tidak ditemukan');
        }

        $filePath = WRITEPATH . 'uploads/catalogs/' . $catalog['file_name'];
        if (!file_exists($filePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File tidak ditemukan');
        }

        return $this->response->download($filePath, null);
    }
}