<?php

namespace App\Core\Services;

use Exception;

class RandomGenerator
{

    /**
     * @throws Exception
     */
    public static function generateRandomToken( $length = 6 )
    {
        $token = strtoupper( bin2hex( random_bytes( intdiv( $length, 2 ) ) ) );
    }

}