<?php

namespace App\Controllers\Api\V1;

use App\Controllers\Api\V1\Authorization\Authority;
use App\Exceptions\Backend\RecordNotFoundException;
use CodeIgniter\RESTful\ResourceController;

class ProductApiController extends ResourceController
{
    use Authority;

    protected $modelName = 'App\Models\Product';

    public function index()
    {
        $this->authorize();

        $products = $this->model->findAll();
        
        return $this->respond([
            'code' => 200,
            'message' => 'success',
            'data' => $products,
        ]);
    }

    public function show($id = null)
    {
        $this->authorize();

        $product = $this->model->find($id);
        
        if ($product === null) {
            throw new RecordNotFoundException('Produk tidak ditemukan.');
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
        $this->authorize();

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
    }

    public function update($id = null)
    {
        $this->authorize();

        $data = [
            'code' => $this->request->getVar('code'),
            'name' => $this->request->getVar('name'),
            'price' => $this->request->getVar('price'),
        ];

        $result = $this->model->updateOrFail($id, $data);
            
        if ($result) {
            return $this->respond([
                'code' => 200,
                'message' => 'success',
            ]);
        }
    }

    public function delete($id = null)
    {
        $this->authorize();

        $result = $this->model->deleteOrFail($id);
            
        if ($result) {
            return $this->respond([
                'code' => 200,
                'message' => 'success',
            ]);
        }
    }
}
