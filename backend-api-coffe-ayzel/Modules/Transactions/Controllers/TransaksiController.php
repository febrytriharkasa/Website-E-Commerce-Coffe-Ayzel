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
            'transaksi' => $this->transaksiModel->orderBy('tgl_transaksi', 'DESC')->paginate(5, 'transaksi'),
            'pager'     => $this->transaksiModel->pager // Kirim objek pager ke view
        ];

        return view('Modules\Transactions\Views\index', $data); 
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Transkasi',
            'proudcts' => $this->sizeModel->getSizesWithNameProduct()
        ];

        return view('Modules\Transactions\Views\create', $data);
    }

    public function store()
    {
        $tgl_transaksi = $this->request->getPost('tgl_transaksi');
        $status_transaksi = $this->request->getPost('status_transaksi');
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
            'total_pembayaran' => 0, // dibuat 0 dulu akan di update setelah menghuting detail transaksi
            'status_transaksi' => $status_transaksi
        ];

        $this->transaksiModel->insert($dataTransaksi);

        // Mengambil id dari tb_transaksi disimpan ke transaksi_id
        $transaksi_id = $this->transaksiModel->getInsertID();

        $totalPembayaran = 0;

        // looping dan insert ke tb_detail_transaksi
        for ($i = 0; $i < count($size_product_ids); $i++) {
            $size_id = $size_product_ids[$i];
            $qty = $qtys[$i];

            // Ambil data harga dari tb_size_produk
            $sizeData = $this->sizeModel->find($size_id);
            $stok = $sizeData['stok'];

            // Hitung Harga
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

            // Kurangi stok lalu update
            $stok_baru = $stok - $qty;
            $this->sizeModel->update($size_id, ['stok' => $stok_baru]);

            if ($qty > $stok) {

                $this->db->transRollback();
                return redirect()->back()->with('error', 'Transaksi dibatalkan karean stok tidak boleh lebih dari qty');
            }
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

    public function edit($id)
    {
        $data = [
            'Title' => 'Edit Transaksi',
            'products' => $this->sizeModel->getSizesWithNameProduct(),
            'detail' => $this->detailModel->getSizesProductsWithDetails($id),
            'transaksi' => $this->transaksiModel->find($id)
        ];

        return view('Modules\Transactions\Views\edit', $data);
    }

    public function update($id)
    {
        $status_transaksi = $this->request->getPost('status_transaksi');
        $size_product_ids = $this->request->getPost('size_product_id');
        $qtys = $this->request->getPost('qty');
        
        if (empty($qtys)) {
            return redirect()->back()->with('error', 'Stok tidak boleh kosong!');
        }
        
        $this->db->transStart();

        $transaksiLama = $this->transaksiModel->find($id);
        $detailLama = $this->detailModel->where('transaksi_id', $id)->findAll();
        
        // 1. Simpan riwayat harga lama ke dalam array sebelum detail dihapus
        // Ini berfungsi untuk "membekukan" harga agar tidak ikut harga master terbaru
        $hargaBeku = [];

        if ($transaksiLama['status_transaksi'] != 'batal') {
            foreach ($detailLama as $old) {
                // Simpan harga lama berdasarkan ID produk
                $hargaBeku[$old['size_product_id']] = [
                    'harga_modal'  => $old['harga_modal'],
                    'harga_satuan' => $old['harga_satuan']
                ];

                // Kembalikan stok lama
                $sizeLama = $this->sizeModel->find($old['size_product_id']);
                if ($sizeLama) {
                    $stokKembali = $sizeLama['stok'] + $old['qty'];
                    $this->sizeModel->update($old['size_product_id'], ['stok' => $stokKembali]);
                }
            }
        } else {
            // Jika status lama batal, kita tetap perlu menyimpan harga lamanya
            foreach ($detailLama as $old) {
                $hargaBeku[$old['size_product_id']] = [
                    'harga_modal'  => $old['harga_modal'],
                    'harga_satuan' => $old['harga_satuan']
                ];
            }
        }

        // Delete semua detail lama dan ulang insert
        $this->detailModel->where('transaksi_id', $id)->delete();

        $totalPembayaran = 0;

        for ($i = 0; $i < count($size_product_ids); $i++) {
            $size_id = $size_product_ids[$i];
            $qty = $qtys[$i];

            // Ambil data dari master HANYA untuk mengecek sisa stok terbaru
            $sizeData = $this->sizeModel->find($size_id);
            $stok = $sizeData['stok'];
            
            if ($status_transaksi != 'batal'){
                if ($qty > $stok) {
                    $this->db->transRollback(); 
                    return redirect()->back()->withInput()->with('error', 'Update dibatalkan! Qty melebihi stok. Sisa stok tersedia: ' . $stok);
                }
            }
            
            // 2. LOGIKA HARGA (FROZEN PRICE)
            // Cek apakah produk ini sudah ada di transaksi lama?
            if (isset($hargaBeku[$size_id])) {
                // Jika ya, gunakan harga saat transaksi itu dibuat
                $harga_modal  = $hargaBeku[$size_id]['harga_modal'];
                $harga_satuan = $hargaBeku[$size_id]['harga_satuan'];
            } else {
                // Jika tidak (berarti admin menambah item BARU ke transaksi ini), ambil harga terbaru
                $harga_modal  = $sizeData['harga_modal'];
                $harga_satuan = $this->sizeModel->getDiskon($sizeData);
            }

            $subtotal_modal = $harga_modal * $qty;
            $subtotal       = $harga_satuan * $qty;

            $dataDetail = [
                'transaksi_id'    => $id,
                'size_product_id' => $size_id,
                'qty'             => $qty,
                'harga_modal'     => $harga_modal,
                'harga_satuan'    => $harga_satuan,
                'subtotal_modal'  => $subtotal_modal,
                'subtotal'        => $subtotal
            ];

            $this->detailModel->insert($dataDetail);

            $totalPembayaran += $subtotal;

            if ($status_transaksi != 'batal') {
                $stok_baru = $stok - $qty;
                $this->sizeModel->update($size_id, ['stok' => $stok_baru]);
            }
        }

        $this->transaksiModel->update($id, [
            'total_pembayaran' => $totalPembayaran,
            'status_transaksi' => $status_transaksi
        ]);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal edit transaksi.');
        }

        return redirect()->to('/transaksi')->with('success', 'Transaksi berhasil diedit');
    }

    public function delete ($id)
    {
        $this->db->transStart();
        $transaksiLama = $this->transaksiModel->find($id);

        $detailLama = $this->detailModel->where('transaksi_id', $id)->findAll();

        if ($transaksiLama['status_transaksi'] != 'batal') {
            foreach ($detailLama as $old) {
                $sizeLama = $this->sizeModel->find($old['size_product_id']);
                if ($sizeLama) {
                    $stokKembali = $sizeLama['stok'] + $old['qty'];
                    $this->sizeModel->update($old['size_product_id'], ['stok' => $stokKembali]);
                }
            }
        }

        // Hapus detail transaksi terlebih dahulu untuk mencegah error
        $this->detailModel->where('transaksi_id', $id)->delete();

        // Hapus tabel transaksi
        $this->transaksiModel->delete($id);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->with('error', 'Data gagal dihapus!');
        }

        return redirect()->to('/transaksi')->with('success', 'Data berhasil dihapus!');
    }

}
