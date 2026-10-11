<?php
	require(__DIR__."/Session.php");
	
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
	
	session_commit();
?>