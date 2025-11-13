<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;
use App\Models\ProductModel;
use App\Models\CategoryModel;

class ProductController extends Controller
{
    use ResponseTrait;

    // GET /products
    public function index()
    {
        $productModel = new ProductModel();
        $data = $productModel->findAll();
        return $this->respond($data);
    }

    // GET /products/{id}
    public function show($id)
    {
        $productModel = new ProductModel();
        $product = $productModel->find($id);

        if (!$product) {
            return $this->failNotFound('Produk tidak ditemukan');
        }

        return $this->respond($product);
    }
    // PUT /products/{id}

    
    public function update($id = null)
    {
        $productModel = new \App\Models\ProductModel();
        $data = $this->request->getJSON(true);

        if (!$data) {
            $data = $this->request->getRawInput(); // buat jaga2 kalau bukan JSON
        }

        if ($productModel->update($id, $data)) {
            return $this->respond(['message' => 'Produk berhasil diperbarui']);
        } else {
            return $this->fail('Gagal memperbarui produk');
        }
    }


    // POST /products
    public function create()
    {
        $data = $this->request->getJSON(true);
        $productModel = new ProductModel();

        if ($productModel->insert($data)) {
            return $this->respondCreated(['message' => 'Produk berhasil ditambahkan']);
        } else {
            return $this->fail('Gagal menambah produk');
        }
    }

    // DELETE /products/{id}
    public function delete($id)
    {
        $productModel = new ProductModel();

        if ($productModel->delete($id)) {
            return $this->respondDeleted(['message' => 'Produk berhasil dihapus']);
        } else {
            return $this->fail('Gagal menghapus produk');
        }
    }

    // GET /categories
    public function getCategories()
    {
        $categoryModel = new CategoryModel();
        $data = $categoryModel->findAll();
        return $this->respond($data);
    }
    
    public function viewProducts()
{
    $productModel  = new ProductModel();
    $categoryModel = new CategoryModel();

    $data = [
        'products'   => $productModel->findAll(),
        'categories' => $categoryModel->findAll()
    ];

    return view('product', $data); // pastikan file product.php ada di app/Views
}

}
