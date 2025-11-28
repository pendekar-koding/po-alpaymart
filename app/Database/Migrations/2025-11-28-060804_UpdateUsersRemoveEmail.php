<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUsersRemoveEmail extends Migration
{
    public function up()
    {
        // Drop and recreate users table without email
        $this->db->query('DROP TABLE IF EXISTS users');
        
        $this->db->query("
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                full_name VARCHAR(100) NOT NULL,
                role VARCHAR(20) DEFAULT 'seller',
                status VARCHAR(20) DEFAULT 'active',
                created_at DATETIME,
                updated_at DATETIME
            )
        ");
        
        // Insert default admin with lowercase username
        $this->db->query("
            INSERT INTO users (username, password, full_name, role, status, created_at) 
            VALUES ('admin', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 'Administrator', 'admin', 'active', NOW())
        ");
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS users');
        
        $this->db->query("
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                full_name VARCHAR(100) NOT NULL,
                role ENUM('admin', 'seller') DEFAULT 'seller',
                status VARCHAR(20) DEFAULT 'active',
                created_at DATETIME,
                updated_at DATETIME
            )
        ");
    }
}