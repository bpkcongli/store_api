<?php

namespace App\Controllers\Api\V1;

use App\Models\Product;
use CodeIgniter\RESTful\ResourceController;

class ProductApiController extends ResourceController
{
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
                    'id' => $productId,
                ],
            ]);
        }
    }
}
