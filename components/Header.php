<?php
if(isset($settings)) {
	$theme_css = "shop-front.css?rev=1.0";
	$tokens_css = "themes/". $shop->clean($settings[0]['settings.theme'],'encode') ."/tokens.css?rev=1.0";
	} else {
	$settings = $db->query("SELECT * from `shop.settings`");
	$theme_css = "shop-front.css?rev=1.0";
	$tokens_css = "themes/". $shop->clean($settings[0]['settings.theme'],'encode') ."/tokens.css?rev=1.0";
}
?>

<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?php echo $shop->clean($meta[0]['meta_description'],'encode');?>">
<meta name="keywords" content="<?php echo $shop->clean($meta[0]['meta_tags'],'encode');?>">
<link rel="stylesheet" href="<?php echo $shop->clean(WEBSITE,'encode');?>assets/css/<?php echo $tokens_css;?>" />
<link rel="stylesheet" href="<?php echo $shop->clean(WEBSITE,'encode');?>assets/css/<?php echo $theme_css;?>" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cantarell:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<title><?php echo $shop->clean($meta[0]['meta_title'],'encode');?></title>