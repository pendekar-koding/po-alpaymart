<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToProductsAndShopName extends Migration
{
    public function up()
    {
        // Add shop_name to users table
        $this->db->query("ALTER TABLE users ADD COLUMN shop_name VARCHAR(100) NULL AFTER full_name");
        
        // Add user_id to products table
        $this->db->query("ALTER TABLE products ADD COLUMN user_id INT NOT NULL DEFAULT 1 AFTER id");
        
        // Update admin shop name
        $this->db->query("UPDATE users SET shop_name = 'Alpay Mart Admin' WHERE role = 'admin'");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE users DROP COLUMN shop_name");
        $this->db->query("ALTER TABLE products DROP COLUMN user_id");
    }
}