<?php
	
	ini_set('display_errors', 0);
	header("X-Frame-Options: DENY"); 
	header("Strict-Transport-Security: max-age=15552000"); // raised max age.
	header("Referrer-Policy: same-origin");
	header("X-Content-Type-Options: nosniff");
 
	session_start(); 

	include("../resources/PHP/Class.DB.php");
	
	if(!isset($_SESSION['admin-uuid']) || empty($_SESSION['admin-uuid'])) {
		header("location: ../error/3/");
		exit;
	}

	if($_REQUEST['csrf'] !== $_SESSION['uuid']) {
		header("location: ../error/2/");
		exit;
	}
	
	$_SESSION = array();
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}
	session_destroy();
	header("Location: ../../index.php");
	exit;
?>
