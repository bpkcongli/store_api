<?php

namespace App\Controllers\Api\V1;

use App\Exceptions\InsertRecordFailedException;
use App\Exceptions\RecordNotFoundException;
use CodeIgniter\RESTful\ResourceController;

class ProductApiController extends ResourceController
{
    protected $modelName = 'App\Models\Product';

    public function index()
    {
        $products = $this->model->findAll();
        
        return $this->respond([
            'code' => 200,
            'message' => 'success',
            'data' => $products,
        ]);
    }

    public function show($id = null)
    {
        $product = $this->model->find($id);
        
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
        try {
            $data = [
                'code' => $this->request->getVar('code'),
                'name' => $this->request->getVar('name'),
                'price' => $this->request->getVar('price'),
            ];

            $productId = $this->model->createOrFail($data);
            
            return $this->respondCreated([
                'code' => 201,
                'message' => 'Produk berhasil ditambahkan.',
                'data' => [
                    'productId' => $productId,
                ],
            ]);
        } catch (InsertRecordFailedException $e) {
            return $this->response->setStatusCode(code: 500)->setJSON([
                'code' => 500,
                'message' => 'Gagal menambahkan produk.',
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
            $result = $this->model->updateOrFail($id, $data);
            
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
            $result = $this->model->deleteOrFail($id);
            
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
