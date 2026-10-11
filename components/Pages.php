<?php

	require(__DIR__."/../resources/PHP/Storefront.php");

	
	
	$table    = 'pages';
	$column   = 'page_name';
	$value    =  "/".(isset($_GET['page']) ? (string) $_GET['page'] : '')."/";
	$operator = '*';
	$meta = $db->select($table,$operator,$column,$value);
	
	if(count($meta) <=0) {
		header("Location ../index.php");
		exit;
	}
	
	$table    		= 'components';
	$column   		= 'pid';
	$value    		=  (int) $meta[0]['id'];
	$operator 		= '*';
	$page_result 	= $db->select($table,$operator,$column,$value);
?>
<!DOCTYPE html>
<html>
<head>
<?php
	include("../components/Header.php");
?>	
</head>
<body>
<?php
		include("../components/Head.php"); 
		include("../components/Navigation.php"); 
		include("../components/Page.php");
		include("../components/Footer.php"); 
		include("../components/Scripts.php"); 
?>
</body>
</html>