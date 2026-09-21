<?php

namespace Modules\Transactions\Controllers;

use App\Controllers\BaseController;
use Modules\Products\Models\SizeProductModel;
use Modules\Transactions\Models\TransaksiModel;
use Modules\Transactions\Models\DetailTransaksiModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Test\Constraints\SeeInDatabase;

class TransaksiController extends BaseController
{
    protected $transaksiModel;
    protected $detailModel;
    protected $sizeModel;
    protected $db;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->detailModel = new DetailTransaksiModel();
        $this->sizeModel = new SizeProductModel(); // Asumsi model ini sudah diload
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'title'     => 'Data Transaksi',
            // Gunakan paginate() untuk mendukung pagination di view
            'transaksi' => $this->transaksiModel->orderBy('tgl_transaksi', 'DESC')->paginate(10, 'transaksi'),
            'pager'     => $this->transaksiModel->pager // Kirim objek pager ke view
        ];

        return view('Modules\Transactions\Views\index', $data); 
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Transkasi',
            'proudcts' => $this->sizeModel->select('tb_size_product.*, tb_product.nama')
                                            ->join('tb_product', 'tb_product.id = tb_size_product.produk_id')
                                            ->findAll()
        ];

        return view('Modules\Transactions\Views\create', $data);
    }

    public function store()
    {
        $tgl_transaksi = $this->request->getPost('tgl_transaksi');
        $size_product_ids = $this->request->getPost('size_product_id');
        $qtys = $this->request->getPost('qty');

        // Validasi input dasar
        if (empty($size_product_ids) || empty($qtys)) {
            return redirect()->back()->with('error', 'Item produk tidak boleh kosong!');
        }

        // Mulai database transaksi menggunakan transStart
        $this->db->transStart();

        // Insert ke tb_transaksi
        $dataTransaksi = [
            'kode_transaksi' => 'TRX-' . date('YmdHis'),
            'tgl_transaksi' => $tgl_transaksi,
            'total_pembayaran' => 0 // dibuat 0 dulu akan di update setelah menghuting detail transaksi
        ];

        $this->transaksiModel->insert($dataTransaksi);
        $transaksi_id = $this->transaksiModel->getInsertID();

        $totalPembayaran = 0;

        // looping dan insert ke tb_detail_transaksi
        for ($i = 0; $i < count($size_product_ids); $i++) {
            $size_id = $size_product_ids[$i];
            $qty = $qtys[$i];

            // Ambil data harga dari tb_size_produk
            $sizeData = $this->sizeModel->find($size_id);

            // Hitung Harga dan stok
            $stok = $sizeData['stok'];
            $harga_modal = $sizeData['harga_modal'];
            $harga_satuan = $this->sizeModel->getDiskon($sizeData);

            $subtotal_modal = $harga_modal * $qty;
            $subtotal = $harga_satuan * $qty;

            $dataDetail = [
                'transaksi_id' => $transaksi_id,
                'size_product_id' => $size_id,
                'qty' => $qty,
                'harga_modal' => $harga_modal,
                'harga_satuan' => $harga_satuan,
                'subtotal_modal' => $subtotal_modal,
                'subtotal' => $subtotal
            ];

            $this->detailModel->insert($dataDetail);

            // Akumulasi total pembayaran
            $totalPembayaran += $subtotal;

            // Kurangi stok
            $stok_baru = $stok - $qty;
            $this->sizeModel->update($size_id, ['stok' => $stok_baru]);
        }

        // Update total_pembayaran di tb_transaksi
        $this->transaksiModel->update($transaksi_id, [
            'total_pembayaran' => $totalPembayaran
        ]);

        // Selesaikan Transaction Database
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi! Periksa logs.');
        }

        return redirect()->to('/transaksi')->with('success', 'Transaksi berhasil ditambahkan');
    }

}
