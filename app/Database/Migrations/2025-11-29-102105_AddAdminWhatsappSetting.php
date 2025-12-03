<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminWhatsappSetting extends Migration
{
    public function up()
    {
        $this->db->table('settings')->insert([
            'key' => 'admin_whatsapp',
            'value' => '6281234567890',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->db->table('settings')->where('key', 'admin_whatsapp')->delete();
    }
}