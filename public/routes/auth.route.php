<?php

declare( strict_types=1 );

use App\Controllers\Users\AuthController;

/**
 * Get all users
 */
post( "/login", function () { AuthController::login(); } );
