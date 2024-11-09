<?php

namespace App\Controllers\Api\V1;

use App\Exceptions\InsertRecordFailedException;
use App\Exceptions\RecordConflictException;
use CodeIgniter\RESTful\ResourceController;

class UserApiController extends ResourceController
{
    protected $modelName = 'App\Models\User';

    public function registration()
    {
        try {
            $data = [
                'username' => $this->request->getVar('username'),
                'email' => $this->request->getVar('email'),
                'password' => $this->request->getVar('password'),
            ];
    
            $userId = $this->model->createOrFail($data);

            return $this->respondCreated([
                'code' => 201,
                'message' => 'User berhasil didaftarkan.',
                'data' => [
                    'userId' => $userId,
                ],
            ]);
        } catch (RecordConflictException $e) {
            return $this->response->setStatusCode(code: 409)->setJSON([
                'code' => 409,
                'message' => 'Username sudah terdaftar.',
            ]);
        } catch (InsertRecordFailedException $e) {
            return $this->response->setStatusCode(code: 500)->setJSON([
                'code' => 500,
                'message' => 'Gagal mendaftarkan user.',
            ]);
        }
    }
}
