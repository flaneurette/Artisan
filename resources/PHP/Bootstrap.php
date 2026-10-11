<?php

// Loads the configuration and the classes, and creates the objects that every page uses. 
// The shop pages include Storefront.php, the dashboard pages include dashboard/core/Header.php; 
// both include this file.

require_once(__DIR__."/../../dashboard/configuration.php");
require_once(__DIR__."/../../dashboard/resources/PHP/Class.DB.php");
require_once(__DIR__."/../../dashboard/resources/PHP/Cryptography.php");
require_once(__DIR__."/../../dashboard/resources/PHP/Upload.php");
require_once(__DIR__."/Artisan.php");

$db 			= new sql();
$shop 			= new Artisan();
$cryptography 	= new Cryptography;

?>
