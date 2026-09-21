<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHargaModalEditHargaJual extends Migration
{
    public function up()
    {
        $field = [
            'harga_modal' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
                'after'      => 'ukuran',
            ]
        ];

        $this->forge->addColumn('tb_size_product', $field);

        $modifyField = [
            'harga' => [
                'name' => 'harga_jual',
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ]
        ];

        $this->forge->modifyColumn('tb_size_product', $modifyField);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_size_product', 'harga_modal');

         $modifyField = [
            'harga' => [
                'name' => 'harga_jual',
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ]
        ];

        $this->forge->modifyColumn('tb_size_product', $modifyField);
    }
}
