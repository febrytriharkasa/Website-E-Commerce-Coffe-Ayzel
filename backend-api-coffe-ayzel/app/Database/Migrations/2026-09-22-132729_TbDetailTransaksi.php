<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TbDetailTransaksi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'transaksi_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'size_product_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'qty'             => ['type' => 'INT', 'constraint' => 11],
            'harga_modal'     => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'harga_satuan'    => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'subtotal'        => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'subtotal_modal'  => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('transaksi_id', 'tb_transaksi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('size_product_id', 'tb_size_product', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_detail_transaksi');
    }

    public function down()
    {
        $this->forge->dropTable('tb_detail_transaksi');
    }
}