<?php


namespace App\Core\Http;


use Exception;

class JSONResponse
{
	private object|array $payload;
	private int $statusCode;


	public function __construct( $data, $statusCode = 200 )
	{
		$this->payload = $data;
		$this->statusCode = $statusCode;
	}

	public function response(): void
	{
		header( "Access-Control-Allow-Origin: *" );
		header( "Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS" );
		header( "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, Origin, Methods" );
		header( "Access-Control-Allow-Credentials: true" );
		header( "Access-Control-Max-Age: 3600" );
		header( "Content-Type: application/json; charset=UTF-8" );

		http_response_code( $this->statusCode );
		echo json_encode( $this->payload );
	}


	public static function invalidResponse( array $payload = [ "message" => "Invalid request" ], int $status_code = 400 ): void
	{
		$response = new JSONResponse( $payload, $status_code );
		$response->response();
	}

	public static function notFound(): void
	{
		$response = new JSONResponse( [ "message" => "Not found" ], 404 );
		$response->response();
	}

	public static function validResponse( array|object $payload = [ "message" => "Success" ] ): void
	{
		$response = new JSONResponse( $payload );
		$response->response();
	}

	/**
	 * Response for exception handling
	 * @param Exception $exception
	 */
	public static function exceptionResponse( Exception $exception ): void
	{
		self::invalidResponse( [ "error" => $exception->getMessage() ] );
	}
}
