<?php

namespace App\Exceptions\Backend;

use Throwable;

class RecordNotFoundException extends ApiException
{
    public function __construct(string $message = 'Resource yang Anda minta tidak ditemukan.', int $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
