<?php

	ini_set('display_errors', 0);
	header("X-Frame-Options: DENY"); 
	header("Strict-Transport-Security: max-age=15552000"); // raised max age.
	header("Referrer-Policy: same-origin");
	header("X-Content-Type-Options: nosniff");
 
	session_start([
    		'cookie_secure' => true,
    		'cookie_httponly' => true,
   	 	'cookie_samesite' => 'Lax',
	];
	
	include("../resources/PHP/Class.DB.php");
	include("Cryptography.php");
	
	// login check
	
	if(!isset($_SESSION['loggedin'])) {
		header("Location: ".SITE."login/");
		exit;	
	}
	
	if(!isset($_SESSION['ip'])) {
		header("Location: ".SITE."login/");
		exit;	
	}
	
	if($_SESSION['ip'] != $_SERVER['REMOTE_ADDR']) {
		header("Location: ".SITE."login/");
		exit;	
	}	
	
	if($_SESSION['loggedin'] != '1') {
		header("Location: ".SITE."login/");
		exit;
	} 
	
	$cryptography = new Cryptography;
	$db = new sql();
	
	if(isset($_SESSION['token'])) {
		$token = $_SESSION['token'];
		} else {
		$token = $cryptography->getToken();
		$_SESSION['token'] = $token;
	}
	
	$_SESSION['admin-uuid'] = $cryptography->uniqueID();
	
	if(!isset($_SESSION['admin-uuid']) || empty($_SESSION['admin-uuid'])) {
		header("location: ../error/3/");
		exit;
	}
	
	// create a new admin token
	if(!isset($_SESSION['uuid'])) {
		$token  = $cryptography->uniqueID();
		$token .= $cryptography->uniqueID();
		$token .= $cryptography->uniqueID();
		$token .= $cryptography->uniqueID();
		$_SESSION['uuid'] = $token;		
	} else {
		$token = $db->clean($_SESSION['uuid'],'encode');
	}
	session_commit();
?>
