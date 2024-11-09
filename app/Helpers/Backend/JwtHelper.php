<?php

namespace App\Helpers\Backend;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
	private const ISS = 'andrian8367@gmail.com';
	private const AUD = 'andrian8367@gmail.com';
	private const MAX_AGE_IN_SECONDS = 1800;
	private const JWT_ALGORITHM = 'HS256';

	public static function generateJWT(string $userId): string
	{
		$jwtSecret = self::getJwtSecret();
		$payload = [
			'iss' => self::ISS,
			'aud' => self::AUD,
			'iat' => time(),
			'exp' => time() + self::MAX_AGE_IN_SECONDS,
			'userId' => $userId,
		];

		return JWT::encode($payload, $jwtSecret, self::JWT_ALGORITHM);
	}

	public static function decodeJWT(string $jwt): array|null
	{
		$jwtSecret = self::getJwtSecret();
		$key = new Key($jwtSecret, self::JWT_ALGORITHM);

		try {
			$decodedToken = JWT::decode($jwt, $key);
			return (array)$decodedToken;
		} catch (Exception $e) {
			return null;
		}
	}

	private static function getJwtSecret(): string
	{
		return getenv('JWT_SECRET');
	}
}
