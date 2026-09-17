<?php

namespace Modules\Products\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Modules\Products\Models\ProductModel;
use Modules\Products\Models\SizeProductModel;

class Produk extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index() {
        $productModel = new ProductModel();
        $sizeModel = new SizeProductModel();

        $products = $productModel->findAll();
        $result = [];

        // Gabungkan produk dengan daftar ukuran masing masing
        foreach ($products as $produk)
        {
            $sizes = $sizeModel->where('produk_id' , $produk['id'])->findAll();

            // Cek apakah produk sudah memiliki varian
            // Ambil ID baik jika ia Array maupun Object
            $produkId = is_array($produk) ? $produk['id'] : $produk->id;

            // Cari varian berdasarkan ID produk
            $sizes = $sizeModel->where('produk_id', $produkId)->findAll();

            // Jika kosong ATAU jumlahnya 0, lewati produk ini
            if (empty($sizes) || count($sizes) === 0) {
                continue; 
            }

            // Looping setiap varian yang ada di dalam produk tersebut
            foreach ($sizes as &$v) {
                // DI SINI FUNGSI DIJALANKAN!
                // Hasil perhitungan diskon disimpan ke dalam index baru bernama 'harga_akhir'
                $v['harga_akhir'] = $sizeModel->getDiskon($v);
            }
            
            $produk['sizes'] = $sizes;
            $result[] = $produk; 
        }

        return $this->respond([
            'status' => true,
            'message' => 'Berhasil mengambil data produk dan ukuran.',
            'data' => $result
        ]);
    }
}
