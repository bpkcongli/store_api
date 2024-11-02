<?php

namespace App\Models;

use App\Exceptions\InsertRecordFailedException;
use App\Exceptions\RecordNotFoundException;
use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['id', 'code', 'name', 'price'];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * @throws InsertRecordFailedException
     */
    public function createOrFail(array $data): string
    {
        $uuid = Uuid::uuid4()->toString();
        $data['id'] = $uuid;

        $this->insert((object)$data);
        $product = $this->find($uuid);

        if (!$product) {
            throw new InsertRecordFailedException();
        }

        return $uuid;
    }

    /**
     * @throws RecordNotFoundException
     */
    public function updateOrFail(string $productId, array $data): bool
    {
        $product = $this->find($productId);

        if (!$product) {
            throw new RecordNotFoundException();
        }

        return $this->update($productId, (object)$data);
    }

    /**
     * @throws RecordNotFoundException
     */
    public function deleteOrFail(string $productId): bool
    {
        $product = $this->find($productId);

        if (!$product) {
            throw new RecordNotFoundException();
        }

        return $this->delete($productId);
    }
}
