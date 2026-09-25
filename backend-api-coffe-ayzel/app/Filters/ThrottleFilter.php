<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class ThrottleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = Services::throttler();
        // Batasi 60 request per menit per IP
        if ($throttler->check(md5($request->getIPAddress()), 60, MINUTE) === false) {
            return Services::response()
                ->setStatusCode(429)
                ->setJSON(['status' => false, 'message' => 'Too many requests']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}