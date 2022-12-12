<?php


use App\Core\Http\AppRequest;
use App\Core\Http\JSONResponse;
use App\Core\Services\Validator;
use App\Models\AppUser;
use Carbon\Carbon;

get( '/test/date', function () {

	try {

		$date = AppRequest::getDate( 'date' );

		JSONResponse::validResponse( [
			"date" => $date->toDateString(),
		] );


	} catch ( Exception $exception ) {
		JSONResponse::exceptionResponse( $exception );
	}

} );

get( '/test/where', function () {


	try {
		$user = AppUser::where( 'username', 'dave1' )->first();

		if ( is_null( $user ) ) throw new Exception( 'Invalid user' );


		JSONResponse::validResponse( [
			'data' => $user->password_hash,
		] );


	} catch ( Exception $exception ) {
		JSONResponse::exceptionResponse( $exception );
	}

} );


get( '/test/validator', function () {

	try {

		$value = AppRequest::getInteger( 'value' );


		JSONResponse::validResponse( [
			'data' => $value,
			'valid' => Validator::numberBetween( $value, 5, 10 ),
		] );

	} catch ( Exception $exception ) {
		JSONResponse::exceptionResponse( $exception );
	}

} );