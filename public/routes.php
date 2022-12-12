<?php

declare( strict_types=1 );

require_once __DIR__ . '/router.php';
require_once "../bootstrap.php";


require_once __DIR__ . "/routes/auth.route.php";
require_once __DIR__ . "/routes/users.route.php";

require_once __DIR__ . "/routes/test.route.php";


any( '/404', "404.php" );
