<?php

	session_start();
	
	require("dashboard/configuration.php");
	include("dashboard/resources/PHP/Class.DB.php");
	include("dashboard/resources/PHP/Cryptography.php");
	include("resources/PHP/Artisan.php");
	
	$db = new sql();
	$shop = new Artisan();
	$cryptography = new Cryptography;
	
	$menu			= $db->query("SELECT * FROM `shop.categories` order by `category.order` ASC");
	$pages			= $db->query("SELECT * FROM `pages` order by ordering ASC");
	$meta			= $db->query("SELECT * FROM `pages` WHERE id = 1");
	$highlight		= $db->query("SELECT * FROM `shop` WHERE `product.featured` = 1 ORDER BY id DESC LIMIT 1");
	$carousel		= $db->query("SELECT * FROM `shop` WHERE `product.featured.carousel` = 1 ORDER BY RAND() LIMIT 10");
	$latest			= $db->query("SELECT * FROM `shop` ORDER BY RAND() LIMIT 10");
	$settings		= $db->query("SELECT * from `shop.settings`"); 

	if(isset($_SESSION['token'])) {
		$token = $_SESSION['token'];
		} else {
		$token = $cryptography->getToken();
		$_SESSION['token'] = $token;
	}
?>
<!DOCTYPE html>
<html>
<head>
<?php
		include("components/Header.php");
?>	
</head>
<body>
<?php
		include("components/Head.php"); 
		include("components/Navigation.php"); 
		include("components/Search.php"); 
		include("components/Announcement.php"); 
		
		if(isset($highlight) && count($highlight) >=1) {
			include("components/ShopHighlight.php");
		}
		if(isset($carousel) && count($carousel) >=1) {
			include("components/ShopCarousel.php");
		}
		include("components/ShopContent.php");
		include("components/Footer.php"); 
		include("components/Scripts.php"); 
?>
</body>
</html>