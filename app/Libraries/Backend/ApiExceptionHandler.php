<?php

namespace App\Libraries\Backend;

use App\Exceptions\InvalidAuthorizationException;
use CodeIgniter\Debug\BaseExceptionHandler;
use CodeIgniter\Debug\ExceptionHandlerInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class ApiExceptionHandler extends BaseExceptionHandler implements ExceptionHandlerInterface
{
	public function handle(Throwable $exception, RequestInterface $request, ResponseInterface $response, int $statusCode, int $exitCode): void
	{
		if ($exception instanceof InvalidAuthorizationException) {
			$response->setStatusCode(403);
			$response->setJSON([
                'code' => 403,
                'message' => 'Anda tidak memiliki akses untuk resource ini.',
            ]);

			$response->send();
			exit($exitCode);
		}
	}
}
