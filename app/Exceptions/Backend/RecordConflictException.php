<?php

namespace App\Exceptions\Backend;

use Throwable;

class RecordConflictException extends ApiException
{
    public function __construct(string $message = 'Terjadi konflik pada resource.', int $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
