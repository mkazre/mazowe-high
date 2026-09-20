<?php

namespace App\Filters;

use App\Libraries\JwtService;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeaderLine('Authorization');
        if (! $header || ! str_starts_with($header, 'Bearer ')) {
            return service('response')->setJSON(['ok' => false, 'error' => 'Missing token'])->setStatusCode(401);
        }

        $token = substr($header, 7);
        $payload = (new JwtService())->validate($token);
        if (! $payload) {
            return service('response')->setJSON(['ok' => false, 'error' => 'Invalid or expired token'])->setStatusCode(401);
        }

        $request->userId = $payload['sub'] ?? null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
