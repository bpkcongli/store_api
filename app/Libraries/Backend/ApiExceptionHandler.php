<?php

namespace App\Libraries\Backend;

use App\Exceptions\Backend\InvalidAuthenticationException;
use App\Exceptions\Backend\InvalidAuthorizationException;
use CodeIgniter\Debug\BaseExceptionHandler;
use CodeIgniter\Debug\ExceptionHandlerInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class ApiExceptionHandler extends BaseExceptionHandler implements ExceptionHandlerInterface
{
	public function handle(Throwable $exception, RequestInterface $request, ResponseInterface $response, int $statusCode, int $exitCode): void
	{
		if ($exception instanceof InvalidAuthenticationException) {
			$response->setStatusCode(401);
			$response->setJSON([
                'code' => 401,
                'message' => 'Kredensial yang Anda masukkan tidak sesuai.',
            ]);
		} else if ($exception instanceof InvalidAuthorizationException) {
			$response->setStatusCode(403);
			$response->setJSON([
                'code' => 403,
                'message' => 'Anda tidak memiliki akses untuk resource ini.',
            ]);
		}

		$response->send();
		exit($exitCode);
	}
}
