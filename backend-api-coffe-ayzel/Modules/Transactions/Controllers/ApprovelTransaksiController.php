<?php

namespace Modules\Transactions\Controllers;

use App\Controllers\BaseController;
use Modules\Products\Models\SizeProductModel;
use Modules\Transactions\Models\TransaksiModel;
use Modules\Transactions\Models\DetailTransaksiModel;
use CodeIgniter\HTTP\ResponseInterface;

class ApprovelTransaksiController extends BaseController
{
    protected $transaksiModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
    }

    public function index()
    {
        $data = [
            'title'     => 'Data Approvel Transaksi Pending',
            // Gunakan paginate() untuk mendukung pagination di view
            'transaksi' => $this->transaksiModel->where('status_transaksi', 'pending')
                                                ->orderBy('tgl_transaksi', 'DESC')
                                                ->paginate(5, 'transaksi'),
            'pager'     => $this->transaksiModel->pager // Kirim objek pager ke view
        ];

        return view('Modules\Transactions\Views\approvelTransaksi', $data); 
    }

    public function approvelTransaksiAccept($id)
    {
        $this->transaksiModel->update($id, [
            'status_transaksi' => 'selesai'
        ]);

        return redirect()->back()->with('success', 'Transaksi telah disetujui atau selesai!');
    }

    public function approvelTransaksiReject($id)
    {
        $db = \Config\Database::connect();
        $db->transStart(); // Mulai transaksi database agar aman

        $detailModel = new DetailTransaksiModel();
        $sizeModel = new SizeProductModel();

        $this->transaksiModel->update($id, [
            'status_transaksi' => 'batal'
        ]);

        $detailLama = $detailModel->where('transaksi_id', $id)->findAll();

        foreach ($detailLama as $old) {
            $sizeLama = $sizeModel->find($old['size_product_id']);
            if ($sizeLama) {
                $stokKembali = $sizeLama['stok'] + $old['qty'];
                $sizeModel->update($old['size_product_id'], ['stok' =>$stokKembali]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat membatalkan transaksi.');
        }

        return redirect()->back()->with('error', 'Transaksi telah dibatalkan!');
    }
}
