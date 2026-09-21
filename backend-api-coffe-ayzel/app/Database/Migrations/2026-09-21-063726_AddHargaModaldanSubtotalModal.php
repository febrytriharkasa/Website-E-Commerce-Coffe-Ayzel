<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHargaModaldanSubtotalModal extends Migration
{
    public function up()
    {
        $field = [
            'harga_modal' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'after' => 'qty'
            ],
            'subtotal_modal' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
                'after'      => 'subtotal',
            ],
        ];

        $this->forge->addColumn('tb_detail_transaksi', $field);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_detail_transaksi', 'harga_modal');
        $this->forge->dropColumn('tb_detail_transaksi', 'subtotal_modal');
    }
}
