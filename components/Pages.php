<?php

	require("../dashboard/configuration.php");
	include("../dashboard/resources/PHP/Class.DB.php");
	include("../resources/PHP/Artisan.php");
	
	$db 	= new sql();
	$shop 	= new Artisan();
	$menu 	= $db->query("SELECT * FROM `shop.categories` order by `category.order` ASC");
	$pages 	= $db->query("SELECT * FROM `pages` order by ordering ASC");
	
	$table    = 'pages';
	$column   = 'page_name';
	$value    =  "/".$shop->clean($_GET['page'],'navigate')."/";
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
		include("../components/Search.php"); 
		include("../components/Page.php");
		include("../components/Footer.php"); 
		include("../components/Scripts.php"); 
?>
</body>
</html>