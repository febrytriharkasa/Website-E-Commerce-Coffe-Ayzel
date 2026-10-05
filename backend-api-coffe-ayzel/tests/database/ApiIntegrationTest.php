<?php

namespace Tests\Integration;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use Modules\Products\Controllers\Api\Produk;
use Modules\Products\Models\ProductModel;
use Modules\Products\Models\SizeProductModel;
use Modules\Transactions\Controllers\Api\Transaksi;
use Modules\Transactions\Models\TransaksiModel;
use Modules\Transactions\Models\DetailTransaksiModel;

final class ApiIntegrationTest extends CIUnitTestCase
{
    use ControllerTestTrait;
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $refresh = true;
    protected $namespace = 'App';

    public function testGetProductsEmpty(): void
    {
        $result = $this->withURI('http://localhost:8080/api/produk')
            ->controller(Produk::class)
            ->execute('index');

        $this->assertTrue($result->isOK());
        $json = json_decode($result->response()->getBody(), true);
        $this->assertTrue($json['status']);
        $this->assertEmpty($json['data']);
    }

    public function testGetProductsWithSizes(): void
    {
        $prodModel = new ProductModel();
        $sizeModel = new SizeProductModel();

        $prodId = $prodModel->insert([
            'nama' => 'Ayzel Latte',
            'deskripsi' => 'Kopi susu gula aren',
            'jenis' => 'kopi',
            'gambar' => 'latte.webp',
        ]);

        $sizeModel->insert([
            'produk_id' => $prodId,
            'ukuran' => 'Regular',
            'harga_modal' => 10000,
            'harga_jual' => 20000,
            'stok' => 15,
            'diskon' => 10,
            'tipe_diskon' => 'persen',
        ]);

        $result = $this->withURI('http://localhost:8080/api/produk')
            ->controller(Produk::class)
            ->execute('index');

        $this->assertTrue($result->isOK());
        $json = json_decode($result->response()->getBody(), true);
        $this->assertTrue($json['status']);
        $this->assertCount(1, $json['data']);
        $this->assertEquals('Ayzel Latte', $json['data'][0]['nama']);
        $this->assertEquals(18000, $json['data'][0]['sizes'][0]['harga_akhir']);
    }

    public function testCreateTransactionAndReduceStock(): void
    {
        $prodModel = new ProductModel();
        $sizeModel = new SizeProductModel();
        $trxModel = new TransaksiModel();

        $prodId = $prodModel->insert([
            'nama' => 'Caramel Macchiato',
            'deskripsi' => 'Kopi karamel lezat',
            'jenis' => 'kopi',
        ]);

        $sizeId = $sizeModel->insert([
            'produk_id' => $prodId,
            'ukuran' => 'Large',
            'harga_modal' => 12000,
            'harga_jual' => 25000,
            'stok' => 10,
            'diskon' => 0,
            'tipe_diskon' => 'nominal',
        ]);

        $payload = [
            'total_pembayaran' => 50000,
            'items' => [
                [
                    'size_product_id' => $sizeId,
                    'qty' => 2,
                    'harga_modal' => 12000,
                    'harga_satuan' => 25000,
                ]
            ]
        ];

        $request = service('request');
        $request->setBody(json_encode($payload));

        $result = $this->withURI('http://localhost:8080/api/transaksi')
            ->withBody(json_encode($payload))
            ->controller(Transaksi::class)
            ->execute('store');

        $this->assertTrue($result->isOK());
        $json = json_decode($result->response()->getBody(), true);
        $this->assertTrue($json['status']);
        $this->assertNotEmpty($json['kode_transaksi']);

        // Check stock reduced from 10 to 8
        $updatedSize = $sizeModel->find($sizeId);
        $this->assertEquals(8, $updatedSize['stok']);
    }
}
