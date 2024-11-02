<?php

namespace App\Controllers\Api\V1;

use App\Exceptions\RecordNotFoundException;
use App\Models\Product;
use CodeIgniter\RESTful\ResourceController;

class ProductApiController extends ResourceController
{
    public function index()
    {
        $model = new Product();
        $products = $model->findAll();
        
        return $this->respond([
            'code' => 200,
            'message' => 'success',
            'data' => $products,
        ]);
    }

    public function show($id = null)
    {
        $model = new Product();
        $product = $model->find($id);
        
        if ($product === null) {
            return $this->response->setStatusCode(code: 404)->setJSON([
                'code' => 404,
                'message' => 'Produk tidak ditemukan.',
            ]);
        } else {
            return $this->respond([
                'code' => 200,
                'message' => 'success',
                'data' => $product,
            ]);
        }
    }

    public function store()
    {
        $data = [
            'code' => $this->request->getVar('code'),
            'name' => $this->request->getVar('name'),
            'price' => $this->request->getVar('price'),
        ];

        $model = new Product();
        $productId = $model->createProduct($data);

        if ($productId === null) {
            return $this->response->setStatusCode(code: 500)->setJSON([
                'code' => 500,
                'message' => 'Gagal menambahkan produk.',
            ]);
        } else {
            return $this->respondCreated([
                'code' => 201,
                'message' => 'Produk berhasil ditambahkan.',
                'data' => [
                    'productId' => $productId,
                ],
            ]);
        }
    }

    public function update($id = null)
    {
        $data = [
            'code' => $this->request->getVar('code'),
            'name' => $this->request->getVar('name'),
            'price' => $this->request->getVar('price'),
        ];

        try {
            $model = new Product();
            $result = $model->updateOrFail($id, $data);
            
            if ($result) {
                return $this->respond([
                    'code' => 200,
                    'message' => 'success',
                ]);
            }
        } catch (RecordNotFoundException $e) {
            return $this->response->setStatusCode(code: 404)->setJSON([
                'code' => 404,
                'message' => 'Produk tidak ditemukan.',
            ]);
        }
    }

    public function delete($id = null)
    {
        try {
            $model = new Product();
            $result = $model->deleteOrFail($id);
            
            if ($result) {
                return $this->respond([
                    'code' => 200,
                    'message' => 'success',
                ]);
            }
        } catch (RecordNotFoundException $e) {
            return $this->response->setStatusCode(code: 404)->setJSON([
                'code' => 404,
                'message' => 'Produk tidak ditemukan.',
            ]);
        }
    }
}
