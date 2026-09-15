<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TbTransaksi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kode_transaksi'   => ['type' => 'VARCHAR', 'constraint' => 50],
            'tgl_transaksi'    => ['type' => 'DATETIME'],
            'total_pembayaran' => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_transaksi');
    }

    public function down()
    {
        $this->forge->dropTable('tb_transaksi');
    }
}
