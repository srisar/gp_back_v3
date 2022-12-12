<?php

namespace App\Controllers\Users;

use App\Core\Http\AppRequest;
use App\Core\Http\JSONResponse;
use App\Core\Services\JWTService;
use App\Models\AppUser;
use Exception;

class AuthController
{

	public static function login(): void
	{

//		sleep( 2 );

		try {
			$fields = [
				'email' => AppRequest::getString( 'email' ),
				'password' => AppRequest::getString( 'password' ),
			];


			$user = AppUser::where( 'email', $fields[ 'email' ] )->first();
			if ( is_null( $user ) )
				throw new Exception( 'Username or password is incorrect' );

			if ( !password_verify( $fields[ 'password' ], $user->password_hash ) )
				throw new Exception( 'Username or password is incorrect' );


			$jwt = JWTService::encode( [
				'id' => $user->id,
				'role' => $user->role,
			] );

			JSONResponse::validResponse( [
				'user' => [
					'id' => $user->id,
					'email' => $user->email,
					'full_name' => $user->full_name,
					'role' => $user->role,
					'profile_pic' => $user->profile_pic,
				],
				'token' => $jwt,
			] );

		} catch ( Exception $exception ) {
			JSONResponse::exceptionResponse( $exception );
		}

	}

}