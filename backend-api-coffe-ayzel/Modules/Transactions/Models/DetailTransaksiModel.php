<?php

namespace Modules\Transactions\Models;

use CodeIgniter\Model;

class DetailTransaksiModel extends Model
{
    protected $table            = 'tb_detail_transaksi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    // Di DetailTransaksiModel.php
    protected $allowedFields = ['transaksi_id', 'size_product_id', 'qty', 'harga_modal', 'harga_satuan', 'subtotal_modal', 'subtotal'];
    

    protected bool $allowEmptyInserts = true;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getSizesProductsWithDetails($transaksiId)
    {
        return $this->select('tb_detail_transaksi.*, tb_size_produk.ukuran, tb_produk.nama')
        ->join('tb_size_produk', 'tb_size_produk.id = tb_detail_transaksi.size_product_id', 'left')
        ->join('tb_size_product', 'tb_size_product.produk_id = tb_product.id', 'left')
        ->where('tb_detail_transaksi.transaksi_id', $transaksiId)
        ->get()->getResultArray();
    }
}
