<?php

namespace App\Core\Http;

use Carbon\Carbon;
use Exception;
use Laminas\I18n\Validator\IsFloat;
use Laminas\I18n\Validator\IsInt;
use Laminas\Validator\Digits;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\NotEmpty;

class AppRequest
{

	public const REQUEST_GET = 'GET';
	public const REQUEST_POST = 'POST';

	public const METHOD_GET = 'GET';
	public const METHOD_POST = 'POST';
	public const METHOD_PUT = 'PUT';
	public const METHOD_DELETE = 'DELETE';
	public const METHOD_PATCH = 'PATCH';

	/**
	 * Returns the request method: GET, POST, PUT, DELETE and so on..
	 * @return string
	 */
	public static function method(): string
	{
		return $_SERVER[ 'REQUEST_METHOD' ];
	}

	public static function scheme(): string
	{
		return $_SERVER[ 'REQUEST_SCHEME' ];
	}

	public static function host(): string
	{
		return $_SERVER[ 'HTTP_HOST' ];
	}

	/**
	 * Enable Axios support: get data sent by axios into php.
	 * By default, PHP handles form-data, but axios sends data as
	 * raw request
	 * @return array
	 */
	private static function getAxiosData(): array
	{
		$data = json_decode( file_get_contents( "php://input" ), true );

		if ( !is_null( $data ) ) return $data;
		return [];
	}

	public static function mergeAxiosRequest(): void
	{
		$axiosData = self::getAxiosData();
		$_REQUEST = array_merge( $axiosData, $_REQUEST );
	}


	/**
	 * Returns a value for the given key, if available from request.
	 * If required is true and no key is found in the request, exception is thrown.
	 * If required is false and no key is found, then null is returned.
	 * @throws Exception
	 */
	private static function getParam( string $key, $required = false ): ?string
	{
		if ( isset( $_REQUEST[ $key ] ) ) {

			if ( $required ) {

				$validator = new NotEmpty();
				if ( $validator->isValid( $_REQUEST[ $key ] ) ) {
					return strval( $_REQUEST[ $key ] );
				} else {
					throw new Exception( sprintf( '[%s] is required', $key ) );
				}

			}

			return strval( $_REQUEST[ $key ] );

		}
		if ( $required ) throw new Exception( sprintf( '[%s] is required', $key ) );
		return null;
	}


	/**
	 * Returns a string value for the given key.
	 * @throws Exception
	 */
	public static function getString( string $key, bool $required = true, string $default = '' ): string
	{
		$value = self::getParam( $key, $required );
		if ( is_null( $value ) ) return $default;
		return $value;
	}


	/**
	 * Returns an integer value for the given key.
	 * If value is not a valid int, exception is thrown.
	 * @throws Exception
	 */
	public static function getInteger( string $key, bool $required = true, int $default = 0 ): int
	{
		$value = self::getParam( $key, $required );

		if ( !is_null( $value ) ) {

			$validator = new IsInt();
			if ( $validator->isValid( $value ) ) {
				return intval( $value );
			}
			throw new Exception( sprintf( '[%s] is not an integer', $key ) );
		}

		return $default;
	}


	/**
	 * Try to get integer value for the given key; if key is not found,
	 * then default value is returned instead of an exception.
	 * @throws Exception
	 */
	public static function tryInteger( string $key, int $default = 0 ): int
	{
		return self::getInteger( $key, false, $default );
	}


	/**
	 * Returns a float value for the given key.
	 * If value is not a valid float, exception is thrown.
	 * @throws Exception
	 */
	public static function getFloat( string $key, bool $required = true, float $default = 0.0 ): float
	{
		$value = self::getParam( $key, $required );

		if ( !is_null( $value ) ) {

			$validator = new IsFloat();
			if ( $validator->isValid( $value ) ) {
				return floatval( $value );
			}
			throw new Exception( sprintf( '[%s] is not a decimal', $key ) );
		}

		return $default;
	}

	/**
	 * Try to get float value for the given key; if key is not found,
	 * then default value is returned instead of an exception.
	 * @throws Exception
	 */
	public static function tryFloat( string $key, float $default = 0 ): int
	{
		return self::getInteger( $key, false, $default );
	}


	/**
	 * Returns an email formatted value for the given key.
	 * If value is not a valid email address, exception is thrown.
	 * @throws Exception
	 */
	public static function getEmail( string $key, bool $required = true, string $default = "" ): string
	{
		$value = self::getParam( $key, $required );

		if ( !is_null( $value ) ) {

			$validator = new EmailAddress();
			if ( $validator->isValid( $value ) ) {
				return $value;
			}
			throw new Exception( sprintf( '[%s] is not a valid email', $key ) );
		}

		return $default;
	}


	/**
	 * Returns a new Carbon date object from the given key.
	 * If value is not a valid date format, exception is thrown.
	 * Date format can be in many form, best is to use ISO format: YYYY-MM-DD
	 * @throws Exception
	 */
	public static function getDate( string $key, bool $required = true ): Carbon
	{

		$value = self::getParam( $key, $required );

		try {
			return new Carbon( $value );
		} catch ( Exception $exception ) {
			throw new Exception( sprintf( '[%s] is not a valid date format string', $key ) );
		}

	}
}