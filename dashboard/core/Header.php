<?php
	header("X-Frame-Options: DENY"); 
	header("X-XSS-Protection: 1; mode=block"); 
	header("Strict-Transport-Security: max-age=30");
	header("Referrer-Policy: same-origin");
 
	session_start(); 
	
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