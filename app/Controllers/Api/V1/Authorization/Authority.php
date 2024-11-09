<?php

namespace App\Controllers\Api\V1\Authorization;

use App\Exceptions\InvalidAuthorizationException;
use App\Helpers\Backend\JwtHelper;

trait Authority
{
	public function authorize(): string
	{
		$authHeader = $this->request->header('Authorization');
		$authToken = null;

		if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $authToken = $matches[1];
        }

		if (!isset($authToken)) {
			throw new InvalidAuthorizationException();
		}

		$decodedToken = JwtHelper::decodeJWT($authToken);

		if (!isset($decodedToken)) {
			throw new InvalidAuthorizationException();
		}

		return $decodedToken['userId'];
	}
}
