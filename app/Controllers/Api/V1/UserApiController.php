<?php

namespace App\Controllers\Api\V1;

use App\Exceptions\InsertRecordFailedException;
use App\Exceptions\RecordConflictException;
use App\Exceptions\RecordNotFoundException;
use App\Helpers\Backend\JwtHelper;
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

    public function authenticate()
    {
        try {
            $data = [
                'username' => $this->request->getVar('username'),
                'password' => $this->request->getVar('password'),
            ];
    
            $verifyUser = $this->model->verifyUser($data['username'], $data['password']);
            $jwtToken = JwtHelper::generateJWT($verifyUser['id']);

            return $this->response->setStatusCode(code: 200)->setJSON([
                'code' => 200,
                'message' => 'Login berhasil.',
                'data' => [
                    'accessToken' => $jwtToken,
                ],
            ]);
        } catch (RecordNotFoundException $e) {
            return $this->response->setStatusCode(code: 401)->setJSON([
                'code' => 401,
                'message' => 'Username atau email tidak terdaftar.',
            ]);
        }
    }
}
