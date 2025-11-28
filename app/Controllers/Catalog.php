<?php

namespace App\Controllers;

use App\Models\CatalogModel;

class Catalog extends BaseController
{
    public function download($id)
    {
        $catalogModel = new CatalogModel();
        $catalog = $catalogModel->where('id', $id)->where('status', 'active')->first();
        
        if (!$catalog) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Katalog tidak ditemukan');
        }

        $filePath = WRITEPATH . 'uploads/catalogs/' . $catalog['file_name'];
        if (!file_exists($filePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File tidak ditemukan');
        }

        return $this->response->download($filePath, $catalog['title'] . '.' . pathinfo($catalog['file_name'], PATHINFO_EXTENSION));
    }
}