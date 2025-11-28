<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomerOrdersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'order_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'customer_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'customer_whatsapp' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'division_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'payment_method' => [
                'type'       => 'ENUM',
                'constraint' => ['QRIS', 'COD'],
            ],
            'total_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'confirmed', 'completed', 'cancelled'],
                'default'    => 'pending',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('division_id', 'divisions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('customer_orders');
    }

    public function down()
    {
        $this->forge->dropTable('customer_orders');
    }
}