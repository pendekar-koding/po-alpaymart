<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ResetProductTables extends Migration
{
    public function up()
    {
        // Drop existing tables
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->query('DROP TABLE IF EXISTS products');
        $this->db->query('DROP TABLE IF EXISTS categories');
        $this->db->query('DROP TABLE IF EXISTS product_variants');
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        
        // Create products table
        $this->db->query("
            CREATE TABLE products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                image VARCHAR(255),
                status VARCHAR(20) DEFAULT 'active',
                created_at DATETIME,
                updated_at DATETIME
            )
        ");
        
        // Create product_variants table
        $this->db->query("
            CREATE TABLE product_variants (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                variant_name VARCHAR(255) NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                stock INT DEFAULT 0,
                sku VARCHAR(100),
                status VARCHAR(20) DEFAULT 'active',
                created_at DATETIME,
                updated_at DATETIME
            )
        ");
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS product_variants');
        $this->db->query('DROP TABLE IF EXISTS products');
    }
}