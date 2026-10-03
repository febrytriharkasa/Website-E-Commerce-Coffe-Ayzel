<?php

namespace Modules\Sosial\Controllers;

use App\Controllers\BaseController;
use Modules\Sosial\Models\SosialMedia;
use CodeIgniter\HTTP\ResponseInterface;

class SosialMediaController extends BaseController
{
    protected $sosialModel;

    public function __construct()
    {
        $this->sosialModel = new SosialMedia();
    }

    public function index()
    {
        $data = [
            'title' => 'Settings',
            'sosial' => $this->sosialModel->findAll()
        ];

        return view('Modules\Sosial\Views\index', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'key_name'  => 'required',
            'key_value' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Gagal! Nama Platform dan Link/Nomor wajib diisi.');
        }

        // Format nama key agar aman di API
        $keyName = strtolower(str_replace(' ', '_', $this->request->getVar('key_name')));

        $cekDuplikatKeyName = $this->sosialModel->where('key_name', $keyName)->first();
        if ($cekDuplikatKeyName) {
            return redirect()->back()->withInput()->with('error', 'Platform sudah ada');
        }

        $this->sosialModel->insert([
            'key_name' => $keyName,
            'key_value' => $this->request->getVar('key_value')
        ]);

        return redirect()->back()->with('success', 'Sosial media baru berhasil ditambahkan!');
    }

    public function update()
    {
        $ids = $this->request->getVar('id');
        $values = $this->request->getVar('key_value');

        if ($ids && is_array($ids)) {
            // Lakukan looping untuk update setiap baris
            for ($i = 0; $i < count($ids); $i++) {
                $this->sosialModel->update( $ids[$i], [
                    'key_value' => $values[$i]
                ]);
            }
            return redirect()->back()->with('success', 'Perubahan sosial media berhasil disimpan!');
        }
        return redirect()->back()->with('error', 'Tidak ada data yang diubah.');
    }

    public function delete($id)
    {
        $this->sosialModel->delete($id);
        return redirect()->back()->with('success', 'Sosial media berhasil dihapus!');
    }
}
