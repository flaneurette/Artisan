<?php

// Development only: lets PHP's built-in server stand in for Apache's mod_rewrite.
// Mirrors the rules in /.htaccess and /dashboard/.htaccess. Do not deploy this file.
//
// Usage, from the project root:  php -S localhost:8080 dev/router.php

$artisan_root  = dirname(__DIR__);
$artisan_path  = ltrim(rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), '/');
$artisan_slug  = '([a-z\-0-9]+)';

$artisan_rules = [
	// storefront
	['cart/delete/'.$artisan_slug.'/'.$artisan_slug.'/', 'site/cart/index.php', ['delete','token']],
	['cart/', 'site/cart/index.php', []],
	['checkout/', 'site/checkout/index.php', []],
	['checkout/pay/', 'site/checkout/pay.php', []],
	['search/', 'site/search/index.php', []],
	['category/'.$artisan_slug.'/', 'site/category/index.php', ['category']],
	['pages/'.$artisan_slug.'/', 'components/Pages.php', ['page']],
	['shop/([0-9]+)/'.$artisan_slug.'/', 'components/ShopItem.php', ['id','item']],
	// dashboard
	['dashboard/login/', 'dashboard/core/Login.php', []],
	['dashboard/pages/'.$artisan_slug.'/delete/([0-9]+)/', 'dashboard/core/Pages.php', ['csrf','delete']],
	['dashboard/pages/', 'dashboard/core/Pages.php', []],
	['dashboard/pages/add/', 'dashboard/core/AddPage.php', []],
	['dashboard/shop/', 'dashboard/core/Shop.php', []],
	['dashboard/shop/categories/', 'dashboard/core/ShopCategories.php', []],
	['dashboard/shop/categories/'.$artisan_slug.'/delete/([0-9]+)/', 'dashboard/core/ShopCategories.php', ['csrf','delete']],
	['dashboard/shop/categories/edit/([0-9]+)/', 'dashboard/core/EditCategories.php', ['id']],
	['dashboard/shop/add/', 'dashboard/core/AddShopItem.php', []],
	['dashboard/shop/edit/([0-9]+)/', 'dashboard/core/EditShopItem.php', ['pid']],
	['dashboard/shop/'.$artisan_slug.'/delete/([0-9]+)/', 'dashboard/core/Shop.php', ['csrf','delete']],
	['dashboard/components/', 'dashboard/core/Components.php', []],
	['dashboard/components/add/', 'dashboard/core/AddComponents.php', []],
	['dashboard/components/'.$artisan_slug.'/delete/([0-9]+)/', 'dashboard/core/Components.php', ['csrf','delete']],
	['dashboard/resources/', 'dashboard/core/AddResource.php', []],
	['dashboard/pages/edit/([0-9]+)/', 'dashboard/core/EditPages.php', ['pid']],
	['dashboard/components/edit/([0-9]+)/', 'dashboard/core/EditComponents.php', ['pid']],
	['dashboard/lightbox/', 'dashboard/core/Lightbox.php', []],
	['dashboard/shop-settings/', 'dashboard/core/ShopSettings.php', []],
	['dashboard/shop-orders/', 'dashboard/core/ShopOrders.php', []],
	['dashboard/shop-orders/'.$artisan_slug.'/delete/([0-9]+)/', 'dashboard/core/ShopOrders.php', ['token','delete']],
	['dashboard/shop-orders/order-pick/([0-9]+)/'.$artisan_slug.'/', 'dashboard/core/ShopOrderPicking.php', ['id','csrf']],
	['dashboard/shop-customers/', 'dashboard/core/ShopCustomers.php', []],
	['dashboard/shop-invoices/', 'dashboard/core/ShopInvoices.php', []],
	['dashboard/settings/', 'dashboard/core/Settings.php', []],
	['dashboard/logout/'.$artisan_slug.'/', 'dashboard/core/Logout.php', ['csrf']],
	['dashboard/error/([0-9]+)/', 'dashboard/core/Error.php', ['reason']],
	['dashboard/api/', 'dashboard/core/Api.php', []],
];

// Theme logo images: the themes reference cssimage/, which Apache redirects to assets/images/.
if(preg_match('#^assets/css/themes/(white|custom|black|paper)/cssimage/(.*)$#i', $artisan_path, $artisan_match)) {
	header('Location: /assets/images/'.$artisan_match[2], true, 301);
	exit;
}

// The payment library is only included by PHP, see site/mollie/.htaccess.
if(stripos($artisan_path, 'site/mollie/') === 0) {
	http_response_code(403);
	exit;
}

$artisan_script = false;

foreach($artisan_rules as $artisan_rule) {
	if(preg_match('#^'.$artisan_rule[0].'$#i', $artisan_path, $artisan_match)) {
		$artisan_script = $artisan_rule[1];
		foreach($artisan_rule[2] as $artisan_i => $artisan_name) {
			$_GET[$artisan_name] = $artisan_match[$artisan_i + 1];
			$_REQUEST[$artisan_name] = $artisan_match[$artisan_i + 1];
		}
		break;
	}
}

if($artisan_script === false) {
	$artisan_file = $artisan_root.'/'.$artisan_path;
	if(is_dir($artisan_file) && is_file(rtrim($artisan_file,'/').'/index.php')) {
		$artisan_script = rtrim($artisan_path,'/').'/index.php';
	} elseif(is_file($artisan_file) && substr($artisan_file,-4) === '.php') {
		$artisan_script = $artisan_path;
	} elseif(is_file($artisan_file)) {
		return false; // static file, served by the built-in server
	}
}

if($artisan_script === false || !is_file($artisan_root.'/'.$artisan_script)) {
	http_response_code(404);
	echo "404 - not found";
	exit;
}

// The pages use includes relative to their own directory.
chdir(dirname($artisan_root.'/'.$artisan_script));
require $artisan_root.'/'.$artisan_script;
