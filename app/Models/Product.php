<?php

namespace App\Models;

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

    public function createProduct(array $data): ?string
    {
        $uuid = Uuid::uuid4()->toString();
        $data['id'] = $uuid;

        $result = $this->insert((object)$data);
        return $result ? $uuid : null;
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
}
