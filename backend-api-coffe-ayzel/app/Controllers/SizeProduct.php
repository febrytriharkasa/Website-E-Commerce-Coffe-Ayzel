<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\SizeProductModel;
use CodeIgniter\Validation\Rules;

class SizeProduct extends BaseController
{
    protected $productModel;
    protected $sizeModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->sizeModel    = new SizeProductModel();
    }

    public function index()
    {
        helper('number');

        $product = $this->productModel->select('id, nama')->findAll();

        if (empty($product)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Produk Tidak Ditemukan");
        }

        foreach ($product as &$p) {
            $sizes = $this->sizeModel->where('produk_id', $p['id'])->findAll();
            
            // Looping setiap varian yang ada di dalam produk tersebut
            foreach ($sizes as &$v) {
                // DI SINI FUNGSI DIJALANKAN!
                // Hasil perhitungan diskon disimpan ke dalam index baru bernama 'harga_akhir'
                $v['harga_akhir'] = $this->getDiskon($v);
            }
            
            $p['varian'] = $sizes;
        }

        $data = [
            'title'   => 'Kelola Varian Ukuran & Stok',
            'product' => $product,
        ];

        return view('sizeProduct/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Data Produk',
            'product' => $this->productModel->select('id, nama')->findAll()
        ];

        return view('sizeProduct/create', $data);
    }

    // $produk_id diambil otomatis dari URL parameter
    public function store()
    {   
        if (!$this->validate([
            'ukuran' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Masukkan ukuran varian, contoh: 200gr.',
                    'is_unique' => 'Ukuran sudah ada.'
                    ]
            ],
            'harga' => [
                'rules'  => 'required|numeric|greater_than_equal_to[1000]',
                'errors' => [
                    'required'              => 'Masukkan harga kopi.',
                    'numeric'               => 'Harga harus berupa angka.',
                    'greater_than_equal_to' => 'Harga minimal Rp. 1.000.',
                ]
            ],
            'stok' => 'required|numeric',
            'tipe_diskon' => 'required|in_list[nominal,persen]',
            'diskon' => 'numeric|permit_empty|greater_than_equal_to[0]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Pastikan semua field terisi dengan benar.');
        }

        // Ambil data produk dan ukuran dari form
        $produk_id = $this->request->getVar('produk_id');
        $ukuran = $this->request->getVar('ukuran');

        //cek ukuran berdasarkan produk_id -> ukuran
        $cekUkuranSama = $this->sizeModel->where('produk_id', $produk_id)
                                        ->where('ukuran', $ukuran)
                                        ->first();

        // Jika data ditemukan, berarti ukuran tersebut sudah ada di produk ini
        if ($cekUkuranSama) {
            return redirect()->back()->withInput()->with('error', 'Gagal! Ukuran "' . $ukuran . '" sudah ada di produk ini. Silakan buat ukuran lain.');
        }

        $this->sizeModel->insert([
            'produk_id' => $this->request->getVar('produk_id'), // Langsung gunakan ID dari URL
            'ukuran'    => $ukuran,
            'harga'     => $this->request->getVar('harga'),
            'stok'      => $this->request->getVar('stok'),
            'diskon'     => $this->request->getVar('diskon'),
            'tipe_diskon' => $this->request->getVar('tipe_diskon')
        ]);

        return redirect()->to('/sizes-product/')->with('success', 'Varian ukuran berhasil ditambahkan!');
    }

    public function edit ($id)
    {
        $data = [
            'title' => 'Edit varian produk',
            'sizes' => $this->sizeModel->find($id),
            'products' => $this->productModel->select('id, nama')->findAll()
        ];

        // Cek apakah id benar
        if (empty($data['sizes']))
        {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Varian Kopi tidak ada.');
        }

        return view('sizeProduct/edit', $data);

    }

    // Fitur Edit Ukuran & Harga Varian
    public function update($id)
    {
        $sizesOld = $this->sizeModel->find($id);

        if (!$sizesOld) {
            return redirect()->back()->with('error', 'Data varian tidak ditemukan.');
        }

        if (!$this->validate([
            'ukuran' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Masukkan ukuran varian, contoh: 200gr.',
                    'is_unique' => 'Ukuran sudah ada.'
                    ]
            ],
            'harga' => [
                'rules'  => 'required|numeric|greater_than_equal_to[1000]',
                'errors' => [
                    'required'              => 'Masukkan harga kopi.',
                    'numeric'               => 'Harga harus berupa angka.',
                    'greater_than_equal_to' => 'Harga minimal Rp. 1.000.',
                ]
            ],
            'stok' => 'required|numeric',
            'tipe_diskon' => 'required|in_list[nominal,persen]',
            'diskon' => 'numeric|permit_empty|greater_than_equal_to[0]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Pastikan semua field terisi dengan benar.');
        }

        $this->sizeModel->update($id, [
            'ukuran' => $this->request->getVar('ukuran'),
            'harga'  => $this->request->getVar('harga'),
            'stok'   => $this->request->getVar('stok'),
            'diskon'     => $this->request->getVar('diskon'),
            'tipe_diskon' => $this->request->getVar('tipe_diskon')
        ]);

        return redirect()->to('/sizes-product/')->with('success', 'Varian berhasil diperbarui!');
    }

    public function updateStok($id)
    {
        $tambah_stok = $this->request->getPost('tambah_stok');

        if (!is_numeric($tambah_stok) || $tambah_stok < 1) {
            return redirect()->back()->with('error', 'Jumlah stok yang ditambahkan tidak valid.');
        }

        $size = $this->sizeModel->find($id);

        if ($size) {
            $stok_baru = $size['stok'] + $tambah_stok;

            $this->sizeModel->update($id, [
                'stok' => $stok_baru
            ]);

            return redirect()->to('/sizes-product/')->with('success', 'Stok berhasil ditambahkan!');
        }

        return redirect()->back()->with('error', 'Data varian tidak ditemukan!');
    }

    // Fungsi untuk mengatur diskon
    public function getDiskon($sizes)
    {
        $harga = $sizes['harga'];
        $diskon = $sizes['diskon'];

        if ($diskon > 0)
        {
            // Rumus diskon
            if ($sizes['tipe_diskon'] === 'persen')
            {
                $hargaDiskon = $harga - ($harga * ($diskon / 100)); // Rumus diskon persen
            }else
            {
                $hargaDiskon = $harga - $diskon;
            }
            return max(0, $hargaDiskon); // Cegah agar tidak mines
        }
        return $harga;
    }

    public function delete($id)
    {
        $size = $this->sizeModel->find($id);
        if ($size) {
            $this->sizeModel->delete($id);
            return redirect()->back()->with('success', 'Ukuran berhasil dihapus!');
        }
        return redirect()->back()->with('error', 'Data tidak ditemukan!');
    }
}