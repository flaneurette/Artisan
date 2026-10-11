<?php

	require(__DIR__."/resources/PHP/Storefront.php");

	
	
	
	$meta			= $db->query("SELECT * FROM `pages` WHERE id = 1");
	$highlight		= $db->query("SELECT * FROM `shop` WHERE `product.featured` = 1 ORDER BY id DESC LIMIT 1");
	$carousel		= $db->query("SELECT * FROM `shop` WHERE `product.featured.carousel` = 1 ORDER BY RAND() LIMIT 10");
	$latest			= $db->query("SELECT * FROM `shop` ORDER BY RAND() LIMIT 20");

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