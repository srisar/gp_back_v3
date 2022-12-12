<?php


namespace App\Core\Http;


use App\Core\Services\JWTService;
use App\ModelHelpers\UserHelper;
use Exception;

class Auth
{


	/**
	 * Authenticate incoming request against issued JWT
	 */
	public static function authenticateJWT( array $user_types = UserHelper::ROLES_ALL ): void
	{
		if ( DEBUG ) return;

		if ( empty( $_SERVER[ 'HTTP_AUTHORIZATION' ] ) ) {
			JSONResponse::invalidResponse( [ 'message' => 'Authorization header not found' ], 401 );
			exit;
		}

		$matches = [];

		if ( !preg_match( '/Bearer\s(\S+)/', $_SERVER[ 'HTTP_AUTHORIZATION' ], $matches ) ) {
			JSONResponse::invalidResponse( [ 'message' => 'Authorization token not found' ], 401 );
			exit;
		}

		try {
			$data = JWTService::decode( $matches[ 1 ] )->data;

			/*
			 * Checking if the incoming token has user role in it.
			 */
			if ( !isset( $data->role ) ) {
				JSONResponse::invalidResponse( [ 'message' => 'Invalid token' ] );
				exit;
			}

			if ( !in_array( $data->role, $user_types ) ) {
				JSONResponse::invalidResponse( [ 'message' => 'User not authorized' ] );
				exit;
			}

		} catch ( Exception $exception ) {
			JSONResponse::invalidResponse( [ 'message' => $exception->getMessage() ], 401 );
			exit;
		}

	}

}
