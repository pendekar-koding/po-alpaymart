<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class QrisImage extends BaseController
{
    public function show()
    {
        $qrisPath = WRITEPATH . 'qris/alpaymart.jpg';
        
        if (!file_exists($qrisPath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $this->response->setHeader('Content-Type', 'image/jpeg');
        return $this->response->setBody(file_get_contents($qrisPath));
    }
}