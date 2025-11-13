<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getServer('HTTP_AUTHORIZATION') ?: $request->getHeaderLine('Authorization');

        if (!$authHeader) {
            return service('response')->setStatusCode(401)->setJSON(['status' => 401, 'error' => 'Authorization header missing']);
        }

        // Format expected: "Bearer <token>"
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        } else {
            return service('response')->setStatusCode(401)->setJSON(['status' => 401, 'error' => 'Malformed Authorization header']);
        }

        try {
            $secret = env('jwt.secret');
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));

            // optionally attach user data to request so controllers bisa pakai
            $request->user = $decoded->data ?? null;

            return; // ok: allow request continue
        } catch (\Exception $e) {
            return service('response')->setStatusCode(401)->setJSON(['status' => 401, 'error' => 'Invalid or expired token', 'message' => $e->getMessage()]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing
    }
}
