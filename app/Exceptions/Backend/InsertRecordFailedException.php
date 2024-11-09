<?php

namespace App\Exceptions\Backend;

use Throwable;

class InsertRecordFailedException extends ApiException
{
    public function __construct(string $message = 'Resource gagal dibuat.', int $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
