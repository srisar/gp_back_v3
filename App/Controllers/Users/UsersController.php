<?php

namespace App\Controllers\Users;

use App\Core\Http\AppRequest;
use App\Core\Http\JSONResponse;
use App\Core\Services\Validator;
use App\ModelHelpers\UserHelper;
use App\Models\AppUser;
use Exception;

class UsersController
{

	public static function getAllUsers(): void
	{

		//sleep(3);

		try {
			JSONResponse::validResponse( AppUser::all() );
		} catch ( Exception $exception ) {
			JSONResponse::exceptionResponse( $exception );
		}
	}


	public static function getUserById(): void
	{

		try {

			$id = AppRequest::getInteger( 'id' );
			$user = AppUser::find( $id );

			if ( is_null( $user ) ) {
				JSONResponse::notFound();
				exit();
			}

			JSONResponse::validResponse( $user );

		} catch ( Exception $exception ) {
			JSONResponse::exceptionResponse( $exception );
		}
	}


	public static function createUser(): void
	{
		try {

			$fields = [
				'email' => AppRequest::getEmail( 'email' ),
				'full_name' => AppRequest::getString( 'full_name' ),
				'password' => AppRequest::getString( 'password' ),
				'role' => AppRequest::getString( 'role' ),
			];


			/*
			 * Check if username or email already exist.
			 */

			if ( AppUser::where( 'email', $fields[ 'email' ] )->exists() ) {
				throw new Exception( 'Email already exist' );
			}

			if ( AppUser::where( 'email', $fields[ 'email' ] )->exists() ) {
				throw new Exception( 'Email already exist' );
			}


			$user = AppUser::create( [
				'full_name' => $fields[ 'full_name' ],
				'email' => $fields[ 'email' ],
				'role' => $fields[ 'role' ],
				'password_hash' => password_hash( $fields[ 'password' ], PASSWORD_DEFAULT ),
			] );

			JSONResponse::validResponse( $user );

		} catch ( Exception $exception ) {
			JSONResponse::exceptionResponse( $exception );
		}
	}


	public static function updateUser(): void
	{
		try {

			$fields = [
				'id' => AppRequest::getInteger( 'id' ),
				'full_name' => AppRequest::getString( 'full_name' ),
				'role' => AppRequest::getString( 'role' ),
			];


			if ( $fields[ 'id' ] == 1 && $fields[ 'role' ] != UserHelper::ROLE_ADMIN ) {
				throw new Exception( 'Super admin user role cannot be changed' );
			}


			$user = AppUser::find( $fields[ 'id' ] );

			if ( is_null( $user ) ) throw new Exception( 'Invalid user' );


			$user->full_name = $fields[ 'full_name' ];
			$user->role = $fields[ 'role' ];


			$user->save();

			JSONResponse::validResponse( $user );
			exit();

		} catch ( Exception $exception ) {
			JSONResponse::exceptionResponse( $exception );
		}
	}


	public static function updatePassword(): void
	{

		try {

			$fields = [
				'id' => AppRequest::getInteger( 'id' ),
				'old_password' => AppRequest::getString( 'old_password' ),
				'new_password' => AppRequest::getString( 'new_password' ),
			];


			$user = AppUser::find( $fields[ 'id' ] );

			/*
			 * Check user exists
			 */
			if ( is_null( $user ) ) throw new Exception( 'Invalid user' );


			/*
			 * Check old password is correct
			 */
			if ( !password_verify( $fields[ 'old_password' ], $user->password_hash ) )
				throw new Exception( 'Invalid old password' );

			/*
			 * Check new password length
			 */
			if ( Validator::stringMinLength( $fields[ 'new_password' ], 6 ) )
				throw new Exception( 'Password must be 6 characters minimum' );


		} catch ( Exception $exception ) {
			JSONResponse::exceptionResponse( $exception );
		}

	}

}