<?php

declare(strict_types=1);
require(__DIR__ . '\\..\\types\\types.php');
require(__DIR__ . '\\..\\lib\\lib.php');
require(__DIR__ . '\\..\\helpers\\helpers.php');
require(__DIR__ . '\\..\\data\\data-test.php');


// print_r(getToken("192.168.1.95:3070", ["user" => "genionet", "pass" => "genIo08*"]));
// print_r(getTabla("192.168.1.95:3070", ["user" => "genionet", "pass" => "genIo08*"], "usuarios"));
$values = json_decode($USUARIO_RESPONSE_GET_SAMPLE);
// print_r($values);
print_r(makeTypesForTableResponse($values, "usuario"));