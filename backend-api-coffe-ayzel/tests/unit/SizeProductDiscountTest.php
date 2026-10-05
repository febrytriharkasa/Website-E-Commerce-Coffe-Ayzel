<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Products\Models\SizeProductModel;

final class SizeProductDiscountTest extends CIUnitTestCase
{
    public function testGetDiskonWithPercentage(): void
    {
        $model = new SizeProductModel();

        $size = [
            'harga_jual' => 20000,
            'diskon' => 10,
            'tipe_diskon' => 'persen',
        ];

        $finalPrice = $model->getDiskon($size);
        $this->assertEquals(18000, $finalPrice);
    }

    public function testGetDiskonWithNominal(): void
    {
        $model = new SizeProductModel();

        $size = [
            'harga_jual' => 20000,
            'diskon' => 5000,
            'tipe_diskon' => 'nominal',
        ];

        $finalPrice = $model->getDiskon($size);
        $this->assertEquals(15000, $finalPrice);
    }

    public function testGetDiskonZeroDiscount(): void
    {
        $model = new SizeProductModel();

        $size = [
            'harga_jual' => 20000,
            'diskon' => 0,
            'tipe_diskon' => 'persen',
        ];

        $finalPrice = $model->getDiskon($size);
        $this->assertEquals(20000, $finalPrice);
    }

    public function testGetDiskonPreventsNegativePrice(): void
    {
        $model = new SizeProductModel();

        $size = [
            'harga_jual' => 10000,
            'diskon' => 15000,
            'tipe_diskon' => 'nominal',
        ];

        $finalPrice = $model->getDiskon($size);
        $this->assertEquals(0, $finalPrice);
    }
}
