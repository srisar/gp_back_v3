<?php

use App\Core\Http\JSONResponse;

JSONResponse::invalidResponse( [ "message" => "404 not found" ], 404 );
exit;