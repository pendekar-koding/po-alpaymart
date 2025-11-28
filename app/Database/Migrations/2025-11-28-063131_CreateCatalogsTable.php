<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCatalogsTable extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE TABLE catalogs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                file_name VARCHAR(255) NOT NULL,
                file_type VARCHAR(50) NOT NULL,
                file_size INT NOT NULL,
                status VARCHAR(20) DEFAULT 'active',
                created_at DATETIME,
                updated_at DATETIME
            )
        ");
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS catalogs');
    }
}