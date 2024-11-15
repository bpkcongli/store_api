<?php

namespace App\Controllers\Api\V1;

use App\Exceptions\Backend\RecordNotFoundException;
use App\Helpers\Backend\JwtHelper;
use CodeIgniter\RESTful\ResourceController;

class UserApiController extends ResourceController
{
    protected $modelName = 'App\Models\User';

    public function registration()
    {
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
                'message' => $e->getMessage(),
            ]);
        }
    }
}
