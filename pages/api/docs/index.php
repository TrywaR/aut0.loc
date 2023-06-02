<?php
require("vendor/autoload.php");
$openapi = \OpenApi\Generator::scan(['/core/']);
header('Content-Type: application/x-yaml');
echo $openapi->toYaml();