<?php

namespace App\Models;

use App\Exceptions\InsertRecordFailedException;
use App\Exceptions\RecordConflictException;
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
            throw new RecordConflictException();
        }

        $uuid = Uuid::uuid4()->toString();
        $data['id'] = $uuid;
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        $this->insert((object)$data);
        $user = $this->find($uuid);

        if (!$user) {
            throw new InsertRecordFailedException();
        }

        return $uuid;
    }

    private function isUsernameAlreadyExist(string $username): bool
    {
        return $this->where('username', $username)->countAllResults() > 0;
    }
}
