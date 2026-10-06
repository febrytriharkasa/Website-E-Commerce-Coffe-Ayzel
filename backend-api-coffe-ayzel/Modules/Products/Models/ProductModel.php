<?php

namespace Modules\Products\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'tb_product';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['gambar', 'nama', 'deskripsi', 'jenis'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = ['hapusCacheApi'];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = ['hapusCacheApi'];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = ['hapusCacheApi'];

    protected function hapusCacheApi(array $data)
    {
        // Hapus cache API frontend
        cache()->delete('api_daftar_produk_fe');
        
        // Return data agar proses model CI4 bisa berlanjut normal
        return $data;
    }
    
}
