<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/functions.php";

$db 	  = new sql();
$table    = '`shop.settings`';
$column   = '`id`';
$value    =  1;
$operator = '*';
$result = $db->select($table,$operator,$column,$value);
			
if(isset($result[0]['settings.mollie.api'])) {		
	$mollie = new \Mollie\Api\MollieApiClient();
	$mollie->setApiKey($result[0]['settings.mollie.api']);
} else {
	echo "<p>There has been an error: API key for Mollie is not found in shop settings!</p>";
}
