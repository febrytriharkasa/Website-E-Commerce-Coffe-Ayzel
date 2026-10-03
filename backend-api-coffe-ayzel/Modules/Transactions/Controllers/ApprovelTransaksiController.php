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
    protected $detailModel;
    protected $sizeModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->detailModel = new DetailTransaksiModel();
        $this->sizeModel = new SizeProductModel(); 
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

        $this->transaksiModel->update($id, [
            'status_transaksi' => 'batal'
        ]);

        $detailLama = $this->detailModel->where('transaksi_id', $id)->findAll();

        foreach ($detailLama as $old) {
            $sizeLama = $this->sizeModel->find($old['size_product_id']);
            if ($sizeLama) {
                $stokKembali = $sizeLama['stok'] + $old['qty'];
                $this->sizeModel->update($old['size_product_id'], ['stok' =>$stokKembali]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat membatalkan transaksi.');
        }

        return redirect()->back()->with('error', 'Transaksi telah dibatalkan!');
    }

    public function show($id)
    {
         $data = [
            'Title' => 'Detail Transaksi',
            'detail' => $this->detailModel->getSizesProductsWithDetails($id),
            'transaksi' => $this->transaksiModel->find($id),
            'back_url'  => base_url('transaksi-approvel')
        ];

        return view('Modules\Transactions\Views\show', $data);
    }
}
