<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDiskonToTbSizeProduct extends Migration
{
    public function up()
    {
        $field = [
            'diskon' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'harga'
            ],
            'tipe_diskon' => [
                'type' => 'ENUM',
                'constraint' => ['nominal', 'persen'],
                'default' => 'nominal',
                'after' => 'diskon'
            ]
        ];

        $this->forge->addColumn('tb_size_product', $field);
    }

    public function down()
    {
        // Menghapus kolom jika migration di rollback
        $this->forge->dropColumn('tb_size_product', ['diskon', 'tipe_diskon']);
    }
}
