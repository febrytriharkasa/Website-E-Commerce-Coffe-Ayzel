<?php

namespace Modules\Transactions\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Modules\Transactions\Models\TransaksiModel;
use Modules\Transactions\Models\DetailTransaksiModel;
use Modules\Products\Models\SizeProductModel;

class Transaksi extends ResourceController
{

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, Accept');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
        // Tangani preflight request dari browser (OPTIONS)
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit(0);
        }
    }
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
            'total_pembayaran' => 0,
            'status_transaksi' => 'pending'
        ];

        $transaksiModel->insert($transaksiData);
        $transaksi_id = $transaksiModel->getInsertID();

        $totalPembayaran = 0;

        // Simpan item belanjaan ke detail transaksi dan kurangi stok
        foreach ($json['items'] as $item)
        {
            // PERBAIKAN 1: Hapus $this->
            $sizeData = $sizeModel->find($item['size_product_id']);

            if (!$sizeData) {
                $db->transRollback();
                return $this->failNotFound('Produk dengan ID ' . $item['size_product_id'] . ' tidak ditemukan.');
            }
            
            if ($sizeData['stok'] < $item['qty']) {
                $db->transRollback();
                // Gunakan $this->fail() untuk API
                return $this->fail('Transaksi dibatalkan. Stok tidak mencukupi untuk item ini.', 400);
            }

            $subtotal = $item['qty'] * $item['harga_satuan'];
            $subtotal_modal = $item['harga_satuan'] * $item['qty'];
            
            // Perbaikan sebelumnya sudah benar
            $harga_satuan = $sizeModel->getDiskon($sizeData); 

            $detailModel->insert([
                'transaksi_id' => $transaksi_id,
                'size_product_id' => $item['size_product_id'],
                'qty' => $item['qty'],
                'harga_modal' => $item['harga_modal'],
                'harga_satuan' => $harga_satuan,
                'subtotal_modal' => $subtotal_modal,
                'subtotal' => $subtotal
            ]);

            // Akumulasi total pembayaran
            $totalPembayaran += $subtotal;

            $stokBaru = $sizeData['stok'] - $item['qty'];
            $sizeModel->update($item['size_product_id'], ['stok' => $stokBaru]);
        }

        // PERBAIKAN 2: Hapus $this->
        // Update total_pembayaran di tb_transaksi
        $transaksiModel->update($transaksi_id, [
            'total_pembayaran' => $totalPembayaran
        ]);

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
