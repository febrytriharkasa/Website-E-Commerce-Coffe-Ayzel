<?php

namespace Modules\Products\Models;

use CodeIgniter\Model;

class SizeProductModel extends Model
{
    protected $table            = 'tb_size_product';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['produk_id', 'ukuran', 'harga_modal', 'harga_jual', 'stok', 'diskon', 'tipe_diskon'];

    // Fungsi untuk mengatur diskon
    public function getDiskon($sizes)
    {
        $harga_jual = $sizes['harga_jual'];
        $diskon = $sizes['diskon'];

        if ($diskon > 0)
        {
            // Rumus diskon
            if ($sizes['tipe_diskon'] === 'persen')
            {
                $hargaDiskon = $harga_jual - ($harga_jual * ($diskon / 100)); // Rumus diskon persen
            }else
            {
                $hargaDiskon = $harga_jual - $diskon;
            }
            return max(0, $hargaDiskon); // Cegah agar tidak mines
        }
        return $harga_jual;
    }

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
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

}
