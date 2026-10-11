<?php
	
	include("Session.php");

	if(!isset($_REQUEST['csrf']) || $_REQUEST['csrf'] !== $_SESSION['uuid']) {
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