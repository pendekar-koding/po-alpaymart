<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddShopStatusToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'shop_status' => [
                'type' => 'ENUM',
                'constraint' => ['open', 'closed'],
                'default' => 'open',
                'after' => 'shop_name'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'shop_status');
    }
}