<?php

namespace App\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\ProductModel;
use App\Models\SizeProductModel;

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
