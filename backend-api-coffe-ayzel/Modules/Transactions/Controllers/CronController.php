<?php

namespace Modules\Transactions\Controllers;

use App\Controllers\BaseController;
use Modules\Products\Models\SizeProductModel;
use Modules\Transactions\Models\TransaksiModel;
use Modules\Transactions\Models\DetailTransaksiModel;
use CodeIgniter\HTTP\ResponseInterface;

class CronController extends BaseController
{
    public function cancelExpired()
    {
        $db = \Config\Database::connect();
        $transaksiModel = new TransaksiModel();
        $detailModel = new DetailTransaksiModel();
        $sizeModel = new SizeProductModel();

        // Batas waktu edit status ke batal
        $batasWaktu = date('Y-m-d H:i:s', strtotime('-1 hour'));

        // Cari transaksi yang masih pending setelah melewati batas waktu
        $expiredTransactions = $transaksiModel->where('status_transaksi', 'pending')
                                                ->where('tgl_transaksi <=', $batasWaktu)
                                                ->findAll();
        
        if (empty($expiredTransactions)) {
            return $this->response->setJSON(['message' => 'Tidak ada transaksi kedaluwarsa.']);
        }

        $db->transStart();

        foreach ($expiredTransactions as $eT) {
            $detailLama = $detailModel->where('transaksi_id', $eT['id'])->findAll();

            foreach ($detailLama as $old) {
                $sizeLama = $sizeModel->find($old['size_product_id']);
                if ($sizeLama) {
                    $stokKembali = $sizeLama['stok'] + $old['qty'];
                    $sizeModel->update($old['size_product_id'], ['stok' =>$stokKembali]);
                }
            }

            $transaksiModel->update($eT['id'], [
                'status_transaksi' => 'batal',
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengupdate database.']);
        }

        return $this->response->setJSON([
            'status' => 'success', 
            'message' => count($expiredTransactions) . ' transaksi kedaluwarsa berhasil dibatalkan dan stok dikembalikan.'
        ]);
    }
}
