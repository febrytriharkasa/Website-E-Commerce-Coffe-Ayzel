<?php

namespace Modules\Sosial\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Modules\Sosial\Models\SosialMedia;

class SettingsController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */

    public function index()
    {
        $sosialModel = new SosialMedia();

        $sosial = $sosialModel->findAll();

        // Buat format array
         $result = [];
        foreach ($sosial as $s) {
             $result[$s['key_name']] = $s['key_value'];
        }

        return $this->respond([
            'status' => 200, 
            'data' => $result
        ]);
    }

    // Fungsi untuk mengupdate data dari dashboard admin CI4
    public function updateBatch()
    {
        $inputs = $this->request->getJSON(true); // Ambil data JSON dari body

        if (!$inputs) {
            return $this->fail('Tidak ada data yang dikirim', 400);
        }

        foreach ($inputs as $key => $value) {
            $this->model->where('key_name', $key)->set(['key_value' => $value])->update();
        }

        return $this->respondUpdated(['message' => 'Pengaturan berhasil diperbarui']);
    }
}
