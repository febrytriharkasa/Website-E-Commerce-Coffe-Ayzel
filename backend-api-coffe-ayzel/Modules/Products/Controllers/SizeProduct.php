<?php

namespace Modules\Products\Controllers;

use App\Controllers\BaseController;
use Modules\Products\Models\ProductModel;
use Modules\Products\Models\SizeProductModel;

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

        // 1. Ambil limit dinamis (default 5 jika tidak ada request)
        $limit = $this->request->getVar('limit') ?? 5;

        // 2. Ambil data produk dan jalankan pagination
        $product = $this->productModel->select('id, nama, jenis')->paginate($limit, 'size_product');
        
        // 3. Ambil semua ID produk dari hasil pagination
        $productIds = array_column($product, 'id');

        // 4. Mencegah N+1 Query: Ambil SEMUA varian sekaligus yang sesuai dengan kumpulan ID Produk
        // (Hanya memakan 1 query database tambahan, alih-alih melakukan query berulang kali di dalam foreach)
        $allSizes = [];
        if (!empty($productIds)) {
            $sizesData = $this->sizeModel->whereIn('produk_id', $productIds)->orderBy('stok', 'ASC')->findAll();
            
            // Kelompokkan varian berdasarkan produk_id agar mudah dimasukkan ke array produk
            foreach ($sizesData as $size) {
                $size['harga_akhir'] = $this->sizeModel->getDiskon($size); // Hitung diskon
                $allSizes[$size['produk_id']][] = $size;
            }
        }

        // 5. Gabungkan data varian ke dalam masing-masing produk
        foreach ($product as &$p) {
            $p['varian'] = $allSizes[$p['id']] ?? [];
        }

        // 6. Siapkan data untuk view
        $data = [
            'title'   => 'Kelola Varian Ukuran & Stok',
            'product' => $product,
            'pager'   => $this->productModel->pager // Pager dikirim langsung, tanpa di-cache
        ];

        return view('Modules\Products\Views\Sizes-Products\index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Data Produk',
            'product' => $this->productModel->select('id, nama')->findAll()
        ];

        return view('Modules\Products\Views\Sizes-Products\create', $data);
    }

    public function store()
    {   
        // 1. Validasi untuk input array menggunakan tanda bintang (.*)
        if (!$this->validate([
            'produk_id' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Pilih produk kopi terlebih dahulu.']
            ],
            'ukuran.*' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Masukkan ukuran varian, contoh: 200 ml.',
                ]
            ],
            'harga_modal.*' => [
                'rules'  => 'required|numeric|greater_than_equal_to[1000]',
                'errors' => [
                    'required'              => 'Masukkan harga modal kopi.',
                    'numeric'               => 'Harga modal harus berupa angka.',
                    'greater_than_equal_to' => 'Harga modal minimal Rp. 1.000.',
                ]
            ],
            'harga_jual.*' => [
                'rules'  => 'required|numeric|greater_than_equal_to[1000]',
                'errors' => [
                    'required'              => 'Masukkan harga jual kopi.',
                    'numeric'               => 'Harga jual harus berupa angka.',
                    'greater_than_equal_to' => 'Harga jual minimal Rp. 1.000.',
                ]
            ],
            'stok.*' => 'required|numeric',
            'tipe_diskon.*' => 'required|in_list[nominal,persen]',
            'diskon.*' => 'numeric|permit_empty|greater_than_equal_to[0]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Pastikan semua field terisi dengan benar pada setiap varian.');
        }

        // 2. Ambil semua data input (kini menjadi array)
        $produk_id   = $this->request->getVar('produk_id');
        $ukuran      = $this->request->getVar('ukuran'); 
        $harga_modal = $this->request->getVar('harga_modal');
        $harga_jual  = $this->request->getVar('harga_jual');
        $stok        = $this->request->getVar('stok');
        $tipe_diskon = $this->request->getVar('tipe_diskon');
        $diskon      = $this->request->getVar('diskon');

        // 3. Cek apakah ada ukuran yang diinput ganda di form yang sama
        if (count($ukuran) !== count(array_unique($ukuran))) {
            return redirect()->back()->withInput()->with('error', 'Gagal! Terdapat ukuran yang duplikat dalam form Anda.');
        }

        $dataVarian = [];

        // 4. Looping untuk memproses dan mengecek setiap varian
        for ($i = 0; $i < count($ukuran); $i++) {
            
            // Cek ukuran berdasarkan produk_id -> ukuran di Database
            $cekUkuranSama = $this->sizeModel->where('produk_id', $produk_id)
                                            ->where('ukuran', $ukuran[$i])
                                            ->first();

            // Jika data ditemukan, batalkan seluruh proses dan beri pesan error spesifik
            if ($cekUkuranSama) {
                return redirect()->back()->withInput()->with('error', 'Gagal! Ukuran "' . $ukuran[$i] . '" sudah ada di produk ini. Silakan hapus atau ganti ukuran tersebut.');
            }
            
            // Kumpulkan data yang aman untuk di-insert
            $dataVarian[] = [
                'produk_id'   => $produk_id,
                'ukuran'      => $ukuran[$i],
                'harga_modal' => $harga_modal[$i],
                'harga_jual'  => $harga_jual[$i],
                'stok'        => $stok[$i],
                'diskon'      => $diskon[$i] ?? 0,
                'tipe_diskon' => $tipe_diskon[$i]
            ];
        }

        // 5. Insert seluruh data varian sekaligus ke database
        if (!empty($dataVarian)) {
            $this->sizeModel->insertBatch($dataVarian);
        }

        return redirect()->to('/sizes-product/')->with('success', 'Semua varian ukuran berhasil ditambahkan!');
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

        return view('Modules\Products\Views\Sizes-Products\edit', $data);

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
                    ]
            ],
            'harga_modal' => [
                'rules'  => 'required|numeric|greater_than_equal_to[1000]',
                'errors' => [
                    'required'              => 'Masukkan harga modal kopi.',
                    'numeric'               => 'Harga harus berupa angka.',
                    'greater_than_equal_to' => 'Harga minimal Rp. 1.000.',
                ]
            ],
            'harga_jual' => [
                'rules'  => 'required|numeric|greater_than_equal_to[1000]',
                'errors' => [
                    'required'              => 'Masukkan harga jual kopi.',
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
            'harga_modal' => $this->request->getVar('harga_modal'),
            'harga_jual' => $this->request->getVar('harga_jual'),
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

            return redirect()->back()->with('success', 'Stok berhasil ditambahkan!');
        }

        return redirect()->back()->with('error', 'Data varian tidak ditemukan!');
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