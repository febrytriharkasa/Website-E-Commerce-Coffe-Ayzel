<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{   
    protected $helpers = ['form', 'url', 'number'];
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');

        $db = \Config\Database::connect();
        // 6. Stok produk yang habis/terendam (Abaikan Soft Delete)
        $lowStockQuery = $db->table('tb_size_product')
                            ->select('tb_size_product.ukuran, tb_product.nama, tb_size_product.stok')
                            ->join('tb_product', 'tb_product.id = tb_size_product.produk_id')
                            ->where('tb_size_product.stok <', 5)
                            ->where('tb_size_product.deleted_at', null)
                            ->get()
                            ->getResultArray(); 

        $lowStockCount = count($lowStockQuery);

        \Config\Services::renderer()->setVar('low_stock_items', $lowStockQuery);
        \Config\Services::renderer()->setVar('low_stock_count', $lowStockCount);
        
    }
}
