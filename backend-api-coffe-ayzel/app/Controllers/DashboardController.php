<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\TransaksiModel;

class DashboardController extends BaseController
{
    protected $productModel;
    protected $transaksiModel;

    public function __construct()
    {
        //Inisiasi model agar dapat digunakan diseluruh fungsi
        $this->productModel = new ProductModel();
        $this->transaksiModel = new TransaksiModel();
    }

    public function index()
    {
        // Mengambil data menggunakan fungsi Join yang dibuat di model product
        $product = $this->productModel->getProductsWithSizes();

        // Contoh mengambil total transaksi
        $totalTransksi = $this->transaksiModel->countAllResults();

        // Data yang dikirim ke views
        $data = [
            'title' => 'Dashboard | Sistem Penjualan',
            'products' => $product,
            'total_transaksi' => $totalTransksi
        ];

        return view('dashboard/index', $data);

    }
}