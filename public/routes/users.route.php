<?php

declare( strict_types=1 );

use App\Controllers\Users\UsersController;
use App\Core\Http\Auth;
use App\ModelHelpers\UserHelper;

/**
 * Get all users
 */
get( '/users', function () {
	Auth::authenticateJWT( UserHelper::ROLES_ADMIN_MANAGER );
	UsersController::getAllUsers();
} );


get( '/user', function () {
	Auth::authenticateJWT( UserHelper::ROLES_ADMIN_MANAGER );
	UsersController::getUserById();
} );


post( '/user/create', function () {
	Auth::authenticateJWT( UserHelper::ROLES_ADMIN_MANAGER );
	UsersController::createUser();
} );

patch( '/user/update', function () {
	Auth::authenticateJWT( UserHelper::ROLES_ADMIN_MANAGER );
	UsersController::updateUser();
} );

patch( '/user/update-password', function () {
	Auth::authenticateJWT( UserHelper::ROLES_ADMIN_MANAGER );
	UsersController::updatePassword();
} );


patch("/user/update-picture", function () {
	Auth::authenticateJWT(UserHelper::ROLES_ADMIN_MANAGER);
	UsersController::updateProfilePicture();
});
