<?php

namespace Modules\Products\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Modules\Products\Models\ProductModel;
use Modules\Products\Models\SizeProductModel;

class Produk extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $productModel = new ProductModel();
        $sizeModel = new SizeProductModel();

        $products = $productModel->findAll();
        if (empty($products)) {
            return $this->respond(['status' => true, 'data' => []]);
        }

        $allSizes = $sizeModel->findAll();
        $sizesGrouped = [];
        foreach ($allSizes as $size) {
            $size['harga_akhir'] = $sizeModel->getDiskon($size);
            $sizesGrouped[$size['produk_id']][] = $size;
        }

        $result = [];
        foreach ($products as $product) {
            $pId = $product['id'];
            if (empty($sizesGrouped[$pId])) continue;

            $product['sizes'] = $sizesGrouped[$pId];
            $result[] = $product;
        }

        return $this->respond(['status' => true, 'data' => $result]);
    }
}
