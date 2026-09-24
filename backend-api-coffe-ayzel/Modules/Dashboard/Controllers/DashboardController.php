<?php

namespace Modules\Dashboard\Controllers;

use App\Controllers\BaseController;
use Modules\Products\Models\SizeProductModel;
use Modules\Transactions\Models\TransaksiModel;
use Modules\Transactions\Models\DetailTransaksiModel;

class DashboardController extends BaseController
{
    protected $sizeProductModel;
    protected $transaksiModel;
    protected $detailTransaksiModel;

    public function __construct()
    {
        $this->sizeProductModel     = new SizeProductModel();
        $this->transaksiModel       = new TransaksiModel();
        $this->detailTransaksiModel = new DetailTransaksiModel();
    }

    public function index()
    {
        $products = $this->sizeProductModel->getSizesWithNameProduct();

        // 2. Ambil total transaksi (Hanya yang selesai)
        $totalTransaksi = $this->transaksiModel->where('status_transaksi', 'selesai')->countAllResults();

        // 3. Filter waktu (Pastikan menggunakan prefix tb_transaksi agar spesifik)
        $period = $this->request->getGet('period') ?? 'all';
        $dateFilter = '';
        $dateColumn = 'tb_transaksi.tgl_transaksi'; // Perbaikan: Tambahkan prefix tabel

        switch ($period) {
            case 'hari':
                $dateFilter = "DATE($dateColumn) = CURDATE()";
                break;
            case 'minggu':
                $dateFilter = "YEAR_WEEK($dateColumn, 1) = YEAR_WEEK(CURDATE(), 1)";
                break;
            case 'bulan':
                $dateFilter = "MONTH($dateColumn) = MONTH(CURDATE()) AND YEAR($dateColumn) = YEAR(CURDATE())";
                break;
            case 'tahun':
                $dateFilter = "YEAR($dateColumn) = YEAR(CURDATE())";
                break;
        }

        $db = \Config\Database::connect();

        // 4. Hitung Total Keuntungan (Berdasarkan transaksi selesai)
        $builder = $db->table('tb_detail_transaksi');
        $builder->selectSum('tb_detail_transaksi.subtotal', 'total_jual');
        $builder->selectSum('tb_detail_transaksi.subtotal_modal', 'total_modal');
        
        // Perbaikan Error #1054: Join ke tabel transaksi agar bisa membaca tgl_transaksi
        $builder->join('tb_transaksi', 'tb_transaksi.id = tb_detail_transaksi.transaksi_id');
        
        // Filter HANYA transaksi yang selesai
        $builder->where('tb_transaksi.status_transaksi', 'selesai');

        if ($dateFilter) {
            $builder->where($dateFilter);
        }

        $profitData = $builder->get()->getRow();
        $totalKeuntungan = ($profitData->total_jual ?? 0) - ($profitData->total_modal ?? 0);

        // 5. Total penjualan per periode untuk chart (Hanya transaksi selesai)
        $chartBuilder = $db->table('tb_detail_transaksi');
        $chartBuilder->join('tb_transaksi', 'tb_transaksi.id = tb_detail_transaksi.transaksi_id');
        $chartBuilder->where('tb_transaksi.status_transaksi', 'selesai');
        
        if ($dateFilter) {
            $chartBuilder->where($dateFilter);
        }
        
        $chartData = [];

        if ($period === 'all') {
            $chartBuilder->select('DATE(tb_transaksi.tgl_transaksi) as tgl, SUM(tb_detail_transaksi.subtotal) as total', false)
                ->where('tb_transaksi.tgl_transaksi >= DATE_SUB(NOW(), INTERVAL 30 DAY)')
                ->groupBy('DATE(tb_transaksi.tgl_transaksi)')
                ->orderBy('tgl', 'ASC');
        } elseif ($period === 'hari') {
            $chartBuilder->select('tb_transaksi.tgl_transaksi as tgl, SUM(tb_detail_transaksi.subtotal) as total', false)
                ->where('tb_transaksi.tgl_transaksi >= DATE_SUB(NOW(), INTERVAL 7 DAY)')
                ->groupBy('tb_transaksi.tgl_transaksi')
                ->orderBy('tgl', 'ASC');
        } elseif ($period === 'minggu') {
            $chartBuilder->select('YEAR_WEEK(tb_transaksi.tgl_transaksi, 1) as minggu, SUM(tb_detail_transaksi.subtotal) as total', false)
                ->where('tb_transaksi.tgl_transaksi >= DATE_SUB(NOW(), INTERVAL 4 WEEK)')
                ->groupBy('minggu')
                ->orderBy('minggu', 'ASC');
        } elseif ($period === 'bulan') {
            $chartBuilder->select('DATE_FORMAT(tb_transaksi.tgl_transaksi, "%Y-%m") as bulan, SUM(tb_detail_transaksi.subtotal) as total', false)
                ->where('tb_transaksi.tgl_transaksi >= DATE_SUB(NOW(), INTERVAL 6 MONTH)')
                ->groupBy('bulan')
                ->orderBy('bulan', 'ASC');
        } elseif ($period === 'tahun') {
            $chartBuilder->select('YEAR(tb_transaksi.tgl_transaksi) as tahun, SUM(tb_detail_transaksi.subtotal) as total', false)
                ->where('tb_transaksi.tgl_transaksi >= DATE_SUB(NOW(), INTERVAL 3 YEAR)')
                ->groupBy('tahun')
                ->orderBy('tahun', 'ASC');
        }
        
        $chartRows = $chartBuilder->get()->getResultArray();
        foreach ($chartRows as $row) {
            $chartKey = $period === 'tahun' ? $row['tahun'] : 
                        ($period === 'bulan' ? $row['bulan'] : 
                        ($period === 'minggu' ? $row['minggu'] : $row['tgl']));
            $chartData[$chartKey] = $row['total'] ?? 0;
        }

        // 6. Stok produk yang habis/terendam (Abaikan Soft Delete)
        $lowStockQuery = $db->table('tb_size_product');
        $lowStockQuery->where('stok <', 5);
        $lowStockQuery->where('deleted_at', null); // Abaikan data yang sudah di soft delete
        $lowStockCount = $lowStockQuery->countAllResults();

        // 7. Detail stok keluar per periode
        $stockKeluarBuilder = $db->table('tb_detail_transaksi');
        $stockKeluarBuilder->select('tb_size_product.ukuran, SUM(tb_detail_transaksi.qty) as total_keluar, DATE(tb_transaksi.tgl_transaksi) as tgl');
        $stockKeluarBuilder->join('tb_size_product', 'tb_size_product.id = tb_detail_transaksi.size_product_id', 'left');
        $stockKeluarBuilder->join('tb_transaksi', 'tb_transaksi.id = tb_detail_transaksi.transaksi_id', 'left');
        
        $stockKeluarBuilder->where('tb_transaksi.status_transaksi', 'selesai'); // Hanya hitung stok dari transaksi selesai
        $stockKeluarBuilder->where('tb_size_product.deleted_at', null); // Abaikan varian yang dihapus
        
        if ($period !== 'all') {
            $stockKeluarBuilder->where($dateFilter);
        }
        
        $stockKeluarBuilder->groupBy('tb_size_product.ukuran, DATE(tb_transaksi.tgl_transaksi)');
        $stockKeluarRows = $stockKeluarBuilder->get()->getResultArray();

        // 8. Ambil data terbaru stok per ukuran
        $freshProducts = $this->sizeProductModel->getSizesWithNameProduct();

        $data = [
            'title'            => 'Dashboard | Sistem Penjualan',
            'products'         => $freshProducts,
            'total_transaksi'  => $totalTransaksi,
            'total_keuntungan' => $totalKeuntungan,
            'low_stock_count'  => $lowStockCount,
            'chart_data'       => json_encode(array_values($chartData)),
            'chart_labels'     => json_encode(array_keys($chartData)),
            'period'           => $period,
            'stock_keluar'     => $stockKeluarRows,
        ];

        return view('Modules\Dashboard\Views\dashboard', $data);
    }
}