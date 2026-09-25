<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CorsFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $response = service('response');
        
        // 1. Ambil Origin (sumber) dari request frontend
        $origin = $request->getHeaderLine('Origin');
        
        // 2. Daftar URL frontend yang diizinkan 
        // PENTING: Pastikan tidak ada garis miring '/' di akhir URL
        $allowedOrigins = [
            'http://localhost:5173',          // URL untuk tahap Development
            'https://domain-frontend-mu.com'  // URL untuk tahap Production nanti
        ];

        // 3. Cek apakah origin masuk dalam daftar allowedOrigins
        if (in_array($origin, $allowedOrigins, true)) {
            // Jika cocok, set header spesifik untuk URL tersebut
            $response->setHeader('Access-Control-Allow-Origin', $origin);
        }
        
        // Header wajib lainnya
        $response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept');
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE');

        // 4. Tangani Preflight Request dari Browser (OPTIONS)
        if (strtolower($request->getMethod()) === 'options') {
            return $response->setStatusCode(200); 
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
