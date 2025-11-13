<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ProfileController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $user = $this->request->user ?? null; // di-set oleh JWTFilter
        if (!$user) {
            return $this->failUnauthorized('User not found in token');
        }

        return $this->respond([
            'status' => 200,
            'user' => $user
        ]);
    }
   

    public function loginForm()
{
    return view('login');
}


}
