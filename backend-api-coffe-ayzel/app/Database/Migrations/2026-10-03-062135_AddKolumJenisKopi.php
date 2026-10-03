<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKolumJenisKopi extends Migration
{
    public function up()
    {
        $field = [
            'jenis' => [
                'type'       => 'ENUM',
                'constraint' => ['kopi', 'non-kopi'],
                'default'    => 'kopi', 
                'after'      => 'deskripsi'
            ]
        ];

        $this->forge->addColumn('tb_product', $field);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_product', 'jenis');
    }
}
