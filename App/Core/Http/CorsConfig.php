<?php

namespace App\Core\Http;

class CorsConfig
{


	/**
	 * Enable CORS support. This method can be hooked into bootstrap call
	 */
	public static function enableCORS(): void
	{

		header( "Access-Control-Allow-Origin: *" );

		// Access-Control headers are received during OPTIONS requests
		if ( $_SERVER[ "REQUEST_METHOD" ] == "OPTIONS" ) {

			if ( isset( $_SERVER[ "HTTP_ACCESS_CONTROL_REQUEST_METHOD" ] ) )
				// may also be using PUT, PATCH, HEAD etc
				header( "Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS" );

			if ( isset( $_SERVER[ "HTTP_ACCESS_CONTROL_REQUEST_HEADERS" ] ) )
				header( "Access-Control-Allow-Headers: {$_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]}" );

			exit( 0 );
		}

	}

}