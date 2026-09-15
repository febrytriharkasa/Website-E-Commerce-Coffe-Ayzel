<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Dom\Text;
use PHPUnit\Framework\Constraint\Constraint;

class TbProduct extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'gambar' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'nama' => ['type' => 'VARCHAR', 'constraint' => 100],
            'deskripsi' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_product');
    }   

    public function down()
    {
        $this->forge->dropTable('tb_product');
    }
}
