<?php

declare( strict_types=1 );

require_once "vendor/autoload.php";

use App\Core\Http\AppRequest;
use App\Core\Http\CorsConfig;
use App\Core\Services\JWTService;
use App\Core\Services\StorageSystem;
use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

/*
 * Load .ENV support
 */
$dotenv = Dotenv::createImmutable( __DIR__ );
$dotenv->load();


/*
 * Init Laravel Eloquent (Database component)
 */
$capsule = new Capsule();
$capsule->addConnection( [
	'driver' => 'mysql',
	'host' => $_ENV[ 'DB_HOST' ],
	'database' => $_ENV[ 'DB_NAME' ],
	'username' => $_ENV[ 'DB_USERNAME' ],
	'password' => $_ENV[ 'DB_PASSWORD' ],
	'charset' => 'utf8',
	'collation' => 'utf8_unicode_ci',
	'prefix' => '',
] );

$capsule->setAsGlobal();
$capsule->bootEloquent();

/*
 * Load request handing config
 */
CorsConfig::enableCORS();
AppRequest::mergeAxiosRequest();


/*
 * JWT config
 */
JWTService::init( $_ENV[ 'JWT_SECRET' ], $_ENV[ 'JWT_AUDIENCE' ], $_ENV[ 'JWT_AUDIENCE' ] );

/*
 * Load file upload config
 */
StorageSystem::setAdapter(__DIR__. "/public/uploads");


/* set debug as false for production env */
/* debug will disable auth token validation */
if ( isset( $_ENV[ "DEBUG" ] ) ) {
	if ( $_ENV[ "DEBUG" ] == "true" ) define( "DEBUG", true );
	else define( "DEBUG", false );
} else {
	define( "DEBUG", false );
}


function logme( $data ): void
{
	error_log( print_r( $data, true ) );
}