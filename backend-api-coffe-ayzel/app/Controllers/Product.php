<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ProductModel;
use CodeIgniter\HTTP\Files\UploadedFile;

class Product extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        helper('number');

        $product = $this->productModel->findAll();

        $data = [
            'title'    => 'Halaman Data Produk',
            'product' => $product
        ];

        return view('product/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Data Produk',
        ];

        return view('product/create', $data);
    }

    public function store()
    {
        // Melakukan validasi
        if (!$this -> validate([
            'gambar' => [
                'rules' => [
                    'uploaded[gambar]',
                    'max_size[gambar,2048]',
                    'is_image[gambar]',
                    'mime_in[gambar,image/jpg,image/jpeg,image/png]'
                ],
                'errors' => [
                    'uploaded' => 'Pilih gambar terlebih dahulu.',
                    'max_size' => 'File gambar harus dibawah 2 MB.',
                    'mime_in'  => 'Format gambar harus JPG, JPEG, PNG.',
                    'is_image' => 'File yang anda masukkan bukan gambar.'
                ]
            ],
            'nama' => [
                'rules' => 'required|max_length[100]|is_unique[tb_product.nama]',
                'errors'=> [
                    'required'      => 'Nama jenis kopi harus diisi.',
                    'max_length'    => 'Nama terlalu panjang (maksimal 100 karakter).',
                    'is_unique'     => 'Jenis nama kopi sudah ada.'
                ]
            ],
            'deskripsi' => [
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required'      => 'Masukkan deskripsi kopi.',
                    'max_length'    => 'Deskripsi terlalu panjang (maksimal 255 karakter).',
                ]
            ],

        ])) {
            // Cukup dengan withInput(), CI4 menyimpan error ke session secara otomatis
            return redirect()->to('/product/create')->withInput();
        }

        // Mengambil gambar getFile
        $fileGambar = $this->request->getFile('gambar');

        // Buat nama gambar ganti jadi random agar tidak sama
        $namaGambar = $fileGambar->getRandomName();

        // Path gambar arahkan ke folder public/img/
        $fileGambar->move('imgProducts', $namaGambar);

        $this->productModel->save([
            'gambar'    => $namaGambar,
            'nama'      => $this->request->getVar('nama'),
            'deskripsi' => $this->request->getVar('deskripsi')
        ]);

        return redirect()->to('/product')->with('success', 'Data produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data = [
            'title' => "Edit Data Produk",
            'product' => $this->productModel->find($id)
        ];

        // Cek apakah id benar
        if (empty($data['product']))
        {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Nama Kopi tidak ada.');
        }

        return view('product/edit', $data);
    }

    public function update($id)
    {
        // Simpan produk yang ingin di edit ke variabel productLama
        $productLama = $this->productModel->find($id);

        // Buat agar nama saat tidak di edit tidak perlu cek is_unique
        $ruleNama = 'required|max_length[100]';
        if($productLama['nama'] != $this->request->getVar('nama'))
        {
            $ruleNama .= '|is_unique[tb_product.nama]';
        }

        // Melakukan validasi
        if (!$this -> validate([
            'gambar' => [
                'rules' => [
                    'max_size[gambar,2048]',
                    'is_image[gambar]',
                    'mime_in[gambar,image/jpg,image/jpeg,image/png]'
                ],
                'errors' => [
                    'uploaded' => 'Pilih gambar terlebih dahulu.',
                    'max_size' => 'File gambar harus dibawah 2 MB.',
                    'mime_in'  => 'Format gambar harus JPG, JPEG, PNG.',
                    'is_image' => 'File yang anda masukkan bukan gambar.'
                ]
            ],
            'nama' => [
                'rules' => $ruleNama,
                'errors'=> [
                    'required'      => 'Nama jenis kopi harus diisi.',
                    'max_length'    => 'Nama terlalu panjang (maksimal 100 karakter).',
                    'is_unique'     => 'Jenis nama kopi sudah ada.'
                ]
            ],
            'deskripsi' => [
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required'      => 'Masukkan deskripsi kopi.',
                    'max_length'    => 'Deskripsi terlalu panjang (maksimal 255 karakter).',
                ]
            ],

        ])) {
            // Cukup dengan withInput(), CI4 menyimpan error ke session secara otomatis
            return redirect()->to('/product/edit' . $this->request->getVar('id'))->withInput();
        }

        // Mengambil gambar getFile
        $fileGambar = $this->request->getFile('gambar');

        // Cek apakah user mengupload gambar baru (error 4 artinya tidak ada file yang diupload)
        if ($fileGambar->getError() == 4)
        {
            //Gunakan gambar lama
            $namaGambar = $productLama['gambar'];
        }else
        {
            // Buat nama gambar ganti jadi random agar tidak sama
            $namaGambar = $fileGambar->getRandomName();
    
            // Path gambar arahkan ke folder public/img/
            $fileGambar->move('imgProducts', $namaGambar);

            // Hapus Gambar lama dari folder jika ada
            // Hapus gambar lama dari folder jika ada
             $pathGambarLama = 'imgProducts/' . $productLama['gambar'];
            if($productLama['gambar'] != '' && file_exists($pathGambarLama))
            {
                unlink($pathGambarLama);
            }

        }

        $this->productModel->update($id, [
            'gambar'    => $namaGambar,
            'nama'      => $this->request->getVar('nama'),
            'deskripsi' => $this->request->getVar('deskripsi')
        ]);

        return redirect()->to('/product')->with('success', 'Data produk berhasil diedit!');
    }

    public function delete($id)
    {
        $product = $this->productModel->find($id);

        if ($product)
        {
            unlink('imgProducts/' . $product['gambar']);
        }

        $this->productModel->delete($id);

        return redirect()->to('/product')->with('success', 'Data produk berhasil dihapus!');
    }
}
