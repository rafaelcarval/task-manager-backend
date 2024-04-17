<?php

require __DIR__.'/vendor/autoload.php';

use OpenApi\Annotations as OA;

$swagger = \OpenApi\Generator::scan([__DIR__.'/app']);
$swaggerJson = $swagger->toJson();

header('Content-Type: application/json');
echo $swaggerJson;
