<?php

namespace App\Core\Services;

use Laminas\Validator\Between;
use Laminas\Validator\StringLength;

class Validator
{

	/**
	 * Test a given string against maximum length
	 * @param string $stringValue
	 * @param int $max
	 * @return bool
	 */
	public static function stringMaxLength( string $stringValue, int $max = 0 ): bool
	{
		$validator = new StringLength( [ 'max' => $max ] );
		return $validator->isValid( $stringValue );
	}

	/**
	 * Test a given string against minimum length
	 * @param string $stringValue
	 * @param int $min
	 * @return bool
	 */
	public static function stringMinLength( string $stringValue, int $min = 0 ): bool
	{
		$validator = new StringLength();
		$validator->setMin( $min );
		$validator->setEncoding( 'UTF-8' );

		return $validator->isValid( $stringValue );
	}

	/**
	 * Test a string against length between given values
	 * @param string $stringValue
	 * @param int $min
	 * @param int $max
	 * @return bool
	 */
	public static function stringBetweenLength( string $stringValue, int $min = 0, int $max = 0 ): bool
	{
		$validator = new StringLength( [ 'min' => $min, 'max' => $max ] );
		return $validator->isValid( $stringValue );
	}


	public static function numberBetween( int|float $numberValue, int $min = 0, int $max = 0 ): bool
	{
		$validator = new Between( [ 'min' => $min, 'max' => $max ] );
		return $validator->isValid( $numberValue );
	}

}