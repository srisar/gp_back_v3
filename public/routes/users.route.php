<?php

declare( strict_types=1 );

use App\Controllers\Users\UsersController;
use App\Core\Http\Auth;
use App\ModelHelpers\UserHelper;

/**
 * Get all users
 */
get( '/users/all', function () {
	Auth::authenticateJWT( UserHelper::ROLES_ADMIN_MANAGER );
	UsersController::getAllUsers();
} );


get( '/users/single', function () {
	Auth::authenticateJWT( UserHelper::ROLES_ADMIN_MANAGER );
	UsersController::getUserById();
} );


post( '/users/create', function () {
	Auth::authenticateJWT( UserHelper::ROLES_ADMIN_MANAGER );
	UsersController::createUser();
} );

patch( '/users/update', function () {
	Auth::authenticateJWT( UserHelper::ROLES_ADMIN_MANAGER );
	UsersController::updateUser();
} );

patch( '/users/single/update-password', function () {
	Auth::authenticateJWT( UserHelper::ROLES_ADMIN_MANAGER );
	UsersController::updatePassword();
} );