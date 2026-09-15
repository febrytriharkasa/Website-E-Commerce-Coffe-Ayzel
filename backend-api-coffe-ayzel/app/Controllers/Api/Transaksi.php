<?php

namespace App\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\TransaksiModel;
use App\Models\DetailTransaksiModel;
use App\Models\SizeProductModel;

class Transaksi extends ResourceController
{

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart(); // Mulai database transaksi (aman jika ada eror)

        $json = $this->request->getJSON(true);
        $transaksiModel = new TransaksiModel();
        $detailModel = new DetailTransaksiModel();
        $sizeModel = new SizeProductModel();

        // Simpan ke tabel induk (tb_transaksi)
        $transaksiData = [
            'kode_transaksi' => $json['kode_transaksi'],
            'tgl_transaksi' => date('Y-m-d H:i:s'),
            'total_pembayaran' => $json['total_pembayaran'],
        ];

        $transaksiModel->insert($transaksiData);
        $transaksiId = $transaksiModel->getInsertID();

        // Simpan item belanjaan ke detail transaksi dan kurangi stok
        foreach ($json['items'] as $item)
        {
            $subtotal = $item['qyt'] * $item['harga_satuan'];

            $detailModel->insert([
                'transaksi_id' => $transaksiId,
                'size_product_id' => $item['size_product_id'],
                'qty' => $item['qty'],
                'harga_satuan' => $item['harga_satuan'],
                'subtotal' => $subtotal
            ]);

            // Kurangi stok di tb_size_product secara otomatis
            $sizeData = $sizeModel->find($item['size_product_id']);
            if ($sizeData) 
            {
                $stokBaru = $sizeData['stok'] - $item['qty'];
                $sizeModel->update($item['size_product_id'], ['stok' => $stokBaru]);
            }
        }

        $db->transComplete(); // Selesaikan transaksi database

        if ($db->transStatus() === false) {
            return $this->fail('Gagal memproses transaksi keuangan');
        }

        return $this->respondCreated([
            'status' => true,
            'message' => 'Transaksi berhasil disimpan dan stok diperbarui!'
        ]);
    }

}
