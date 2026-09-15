<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TbSizeProduct extends Migration
{
    public function up()
    {
         $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'produk_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'ukuran'      => ['type' => 'VARCHAR', 'constraint' => 50], // Contoh: Regular, Large
            'harga'       => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'stok'        => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('produk_id', 'tb_product', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_size_product');
    }

    public function down()
    {
        $this->forge->dropTable('tb_size_product');
    }
}
