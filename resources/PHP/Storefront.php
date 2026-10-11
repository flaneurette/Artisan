<?php

// Included at the top of every shop page: starts the session, creates the 
// anti-CSRF token and loads the data that the header, navigation and footer need.

session_start();

require_once(__DIR__."/Bootstrap.php");

$settings 		= $db->query("SELECT * from `shop.settings`"); 
$menu 			= $db->query("SELECT * FROM `shop.categories` order by `category.order` ASC");
$pages 			= $db->query("SELECT * FROM `pages` order by ordering ASC");

if(isset($_SESSION['token'])) {
	$token = $_SESSION['token'];
	} else {
	$token = $cryptography->getToken();
	$_SESSION['token'] = $token;
}

?>
