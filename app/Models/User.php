<?php

namespace App\Models;

use App\Exceptions\Backend\InsertRecordFailedException;
use App\Exceptions\Backend\InvalidAuthenticationException;
use App\Exceptions\Backend\RecordConflictException;
use App\Exceptions\Backend\RecordNotFoundException;
use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['id', 'username', 'email', 'password'];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * @throws RecordConflictException
     * @throws InsertRecordFailedException
     */
    public function createOrFail(array $data): string
    {
        if ($this->isUsernameAlreadyExist($data['username'])) {
            throw new RecordConflictException('Username sudah terdaftar.');
        }

        $uuid = Uuid::uuid4()->toString();
        $data['id'] = $uuid;
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        $this->insert((object)$data);
        $user = $this->find($uuid);

        if (!$user) {
            throw new InsertRecordFailedException('Gagal mendaftarkan user.');
        }

        return $uuid;
    }

    /**
     * @throws RecordNotFoundException
     */
    public function verifyUser(string $username, string $password): array
    {
        $user = $this
            ->where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if (!isset($user)) {
            throw new RecordNotFoundException('Username atau email tidak terdaftar.');
        }

        if (!password_verify($password, $user['password'])) {
            throw new InvalidAuthenticationException();
        };

        return $user;
    }

    private function isUsernameAlreadyExist(string $username): bool
    {
        return $this->where('username', $username)->countAllResults() > 0;
    }
}
