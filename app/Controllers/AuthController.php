<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\UserModel;

class AuthController extends BaseController
{
    use ResponseTrait;

    public function login()
    {
        $userModel = new UserModel();

        // Ambil input dari JSON atau form-data
        $input = $this->request->getJSON(true) ?? $this->request->getPost();

        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';

        if (empty($email) || empty($password)) {
            return $this->respond([
                'status' => 400,
                'message' => 'Email dan password wajib diisi'
            ], 400);
        }

        // Ambil user berdasarkan email
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return $this->respond([
                'status' => 401,
                'message' => 'Email tidak ditemukan'
            ], 401);
        }

        // Verifikasi password
        if (!password_verify($password, $user['password']) && $user['password'] !== $password) {
            return $this->respond([
                'status' => 401,
                'message' => 'Password salah'
            ], 401);
        }

        // Ambil secret key
        $key = getenv('jwt_secret') ?: 'product';
        if (!$key) {
            return $this->respond([
                'status' => 500,
                'message' => 'JWT secret tidak ditemukan di .env'
            ], 500);
        }

        // Payload
        $issuedAt   = time();
        $expiration = $issuedAt + (int)(env('jwt.exp') ?? 3600);

        $payload = [
            'iss'  => env('jwt.issuer') ?? 'productList',
            'aud'  => 'users',
            'iat'  => $issuedAt,
            'exp'  => $expiration,
            'data' => [
                'id'    => $user['id'],
                'email' => $user['email']
            ]
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        return $this->respond([ 
            'status' => 200,
            'message' => 'Login berhasil',
            'token' => $token
        ]);
    }

    public function verify()
    {
        $authHeader = $this->request->getHeaderLine('Authorization');

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return $this->respond(['message' => 'Format Authorization salah atau tidak ada'], 401);
        }

        $token = $matches[1];
        $key = env('jwt_secret') ?: config('App')->jwt_secret;

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return $this->respond([
                'status' => 'success',
                'data' => $decoded
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'message' => 'Token tidak valid: ' . $e->getMessage()
            ], 401);
        }
    }
}
