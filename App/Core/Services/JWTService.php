<?php

namespace App\Core\Services;

use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService
{


	private static string $SECRET_KEY = "";
	private static string $ISSUER = "";
	private static string $AUDIENCE = "";

	private static int $issued_at;
	private static int $not_before;
	private static int $expire;

	private static array $token;


	public static function init( $key, $issuer, $audience )
	{
		self::$SECRET_KEY = $key;
		self::$ISSUER = $issuer;
		self::$AUDIENCE = $audience;
	}


	public static function encode( $data ): string
	{

		$_time = new DateTimeImmutable();

		self::$issued_at = $_time->getTimestamp();
		self::$not_before = $_time->getTimestamp();
		self::$expire = $_time->modify( "+360 minutes" )->getTimestamp();

		self::$token = [
			"iss" => self::$ISSUER,
			"aud" => self::$AUDIENCE,
			"iat" => self::$issued_at,
			"nbf" => self::$not_before,
			"exp" => self::$expire,
			"data" => $data,
		];

		return JWT::encode( self::$token, self::$SECRET_KEY, "HS512" );
	}

	public static function decode( $jwt ): object
	{
		return JWT::decode( $jwt, new Key( self::$SECRET_KEY, "HS512" ) );
	}


}