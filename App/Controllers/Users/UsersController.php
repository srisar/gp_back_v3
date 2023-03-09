<?php

namespace App\Controllers\Users;

use App\Core\Http\AppRequest;
use App\Core\Http\JSONResponse;
use App\Core\Services\StorageSystem;
use App\Core\Services\Validator;
use App\ModelHelpers\UserHelper;
use App\Models\AppUser;
use Exception;
use League\Flysystem\FilesystemException;

class UsersController
{
	public static function getAllUsers(): void
	{

		try {
			$page = AppRequest::getInteger( "page", false, 1 );
			$role = AppRequest::getString( "role", false, "ALL" );

			$whereFilters = [];

			if ( $role != "ALL" ) {
				$whereFilters[] = [ "role", "=", $role ];
			}

			$responseData = AppUser::where( $whereFilters )->paginate( 30, [ "*" ], "", $page );

			JSONResponse::validResponse( $responseData );
		} catch ( Exception $exception ) {
			JSONResponse::exceptionResponse( $exception );
		}
	}

	/* ------------------------------------------------------------------------------------------------------------------ */

	public static function getUserById(): void
	{
		try {
			$id = AppRequest::getInteger( "id" );
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

	/* ------------------------------------------------------------------------------------------------------------------ */

	public static function createUser(): void
	{
		try {
			$fields = [
				"email" => AppRequest::getEmail( "email" ),
				"full_name" => AppRequest::getString( "full_name" ),
				"password" => AppRequest::getString( "password" ),
				"role" => AppRequest::getString( "role" ),
			];

			/*
			 * Check if username or email already exist.
			 */

			if ( AppUser::where( "email", $fields[ "email" ] )->exists() ) {
				throw new Exception( "Email already exist" );
			}

			if ( AppUser::where( "email", $fields[ "email" ] )->exists() ) {
				throw new Exception( "Email already exist" );
			}

			$user = AppUser::create( [
				"full_name" => $fields[ "full_name" ],
				"email" => $fields[ "email" ],
				"role" => $fields[ "role" ],
				"password_hash" => password_hash( $fields[ "password" ], PASSWORD_DEFAULT ),
			] );

			JSONResponse::validResponse( $user );
		} catch ( Exception $exception ) {
			JSONResponse::exceptionResponse( $exception );
		}
	}

	/* ------------------------------------------------------------------------------------------------------------------ */

	public static function updateUser(): void
	{
		try {
			$fields = [
				"id" => AppRequest::getInteger( "id" ),
				"full_name" => AppRequest::getString( "full_name" ),
				"role" => AppRequest::getString( "role" ),
			];

			if ( $fields[ "id" ] == 1 && $fields[ "role" ] != UserHelper::ROLE_ADMIN ) {
				throw new Exception( "Super admin user role cannot be changed" );
			}

			$user = AppUser::find( $fields[ "id" ] );

			if ( is_null( $user ) ) {
				throw new Exception( "Invalid user" );
			}

			$user->full_name = $fields[ "full_name" ];
			$user->role = $fields[ "role" ];

			$user->save();

			JSONResponse::validResponse( $user );
			exit();
		} catch ( Exception $exception ) {
			JSONResponse::exceptionResponse( $exception );
		}
	}

	/* ------------------------------------------------------------------------------------------------------------------ */

	/**
	 * Update user password
	 * @return void
	 */
	public static function updatePassword(): void
	{
		try {
			$fields = [
				"id" => AppRequest::getInteger( "user_id" ),
				"old_password" => AppRequest::getString( "old_password", false ),
				"new_password" => AppRequest::getString( "new_password" ),
			];

			$user = AppUser::find( $fields[ "id" ] );

			/*
			 * Check user exists
			 */
			if ( is_null( $user ) ) {
				throw new Exception( "Invalid user" );
			}

			/*
			 * Check old password is correct
			 */
			if ( !empty( $fields[ "old_password" ] ) ) {
				if ( !password_verify( $fields[ "old_password" ], $user->password_hash ) ) {
					throw new Exception( "Old password does not match existing password" );
				}
			}


			/*
			 * Check new password length
			 */
			if ( !Validator::stringMinLength( $fields[ "new_password" ], 6 ) ) {
				throw new Exception( "Password must be at least 6 characters in length" );
			}

			$user->password_hash = password_hash( $fields[ "new_password" ], PASSWORD_DEFAULT );
			$user->save();

			JSONResponse::validResponse( $user );
		} catch ( Exception $exception ) {
			JSONResponse::exceptionResponse( $exception );
		}
	}

	/* ------------------------------------------------------------------------------------------------------------------ */

	public static function updateProfilePicture(): void
	{

		try {

			$fields = [
				'id' => AppRequest::getString( 'id' ),
				'picture_data' => AppRequest::getString( 'picture_data' ),
			];

			$user = AppUser::find( $fields[ 'id' ] );
			if ( is_null( $user ) ) throw new Exception( 'Invalid user' );


			$base64Data = explode( ",", $fields[ 'picture_data' ] )[ 1 ];

			$bin = base64_decode( $base64Data );
			$image = imagecreatefromstring( $bin );

			if ( !$image ) throw new Exception( 'Invalid image uploaded' );

			ob_start();
			imagepng( $image );
			$pngImage = ob_get_contents();

			ob_end_clean();

			$basePath = '/images/profiles/';
			$fileName = uniqid() . ".png";

			$fullFilePath = $basePath . $fileName;

			StorageSystem::get()->write( $fullFilePath, $pngImage );

			$user->profile_pic = $fileName;
			$user->save();

			JSONResponse::validResponse( $user );


		} catch ( FilesystemException|Exception $exception ) {
			JSONResponse::exceptionResponse( $exception );

		}
	}

	/* ------------------------------------------------------------------------------------------------------------------ */

}
