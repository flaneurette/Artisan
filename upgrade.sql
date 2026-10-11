-- Upgrades an Artisan database that was created before October 2026 to the current schema.
-- Make a backup first, then run once:  mysql artisan < upgrade.sql
-- New installations only need artisan.sql.

-- 1. Table names without dots.

RENAME TABLE `shop.cart` TO `shop_cart`, `shop.categories` TO `shop_categories`, `shop.orders` TO `shop_orders`, `shop.settings` TO `shop_settings`;

-- 2. Values that do not fit the new number columns are set to 0.

UPDATE `shop` SET `product.price` = REPLACE(`product.price`,',','.');
UPDATE `shop` SET `product.price` = '0' WHERE `product.price` IS NULL OR `product.price` NOT REGEXP '^[0-9]+(\\.[0-9]+)?$';
UPDATE `shop` SET `product.stock` = 0 WHERE `product.stock` IS NULL;
UPDATE `shop` SET `product.featured` = IF(`product.featured` = '1','1','0'), `product.featured.carousel` = IF(`product.featured.carousel` = '1','1','0');
UPDATE `shop_cart` SET `cart.created` = '0' WHERE `cart.created` NOT REGEXP '^[0-9]+$';
UPDATE `shop_orders` SET `order.date` = '0' WHERE `order.date` NOT REGEXP '^[0-9]+$';
UPDATE `shop_orders` SET `order.total` = '0' WHERE `order.total` NOT REGEXP '^[0-9]+(\\.[0-9]+)?$';
UPDATE `shop_orders` SET `order.shipping.price` = '0' WHERE `order.shipping.price` NOT REGEXP '^[0-9]+(\\.[0-9]+)?$';
UPDATE `shop_settings` SET `settings.free` = '0' WHERE `settings.free` NOT REGEXP '^[0-9]+(\\.[0-9]+)?$';

-- 3. Column names without dots, numbers stored as numbers, and defaults for the columns 
--    Artisan does not always fill, so that MySQL strict mode no longer has to be disabled.

ALTER TABLE `shop` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

ALTER TABLE `shop`
  CHANGE `product.status` `product_status` varchar(255) DEFAULT NULL,
  CHANGE `product.price` `product_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  CHANGE `product.stock` `product_stock` int(11) NOT NULL DEFAULT 0,
  CHANGE `product.tax` `product_tax` varchar(11) DEFAULT NULL,
  CHANGE `product.title` `product_title` varchar(255) DEFAULT NULL,
  CHANGE `product.description` `product_description` mediumtext DEFAULT NULL,
  CHANGE `product.category` `product_category` varchar(255) DEFAULT NULL,
  CHANGE `product.category.id` `product_category_id` int(11) DEFAULT NULL,
  CHANGE `product.category.sub` `product_category_sub` varchar(255) DEFAULT NULL,
  CHANGE `product.category.sub.id` `product_category_sub_id` int(11) DEFAULT NULL,
  CHANGE `product.image` `product_image` varchar(255) DEFAULT NULL,
  CHANGE `product.image.2` `product_image_2` varchar(255) DEFAULT NULL,
  CHANGE `product.image.3` `product_image_3` varchar(255) DEFAULT NULL,
  CHANGE `product.catno` `product_catno` varchar(255) DEFAULT NULL,
  CHANGE `product.format` `product_format` varchar(255) DEFAULT NULL,
  CHANGE `product.type` `product_type` varchar(255) DEFAULT NULL,
  CHANGE `product.weight` `product_weight` varchar(255) DEFAULT NULL,
  CHANGE `product.condition` `product_condition` varchar(255) DEFAULT NULL,
  CHANGE `product.ean` `product_ean` varchar(255) DEFAULT NULL,
  CHANGE `product.sku` `product_sku` varchar(255) DEFAULT NULL,
  CHANGE `product.vendor` `product_vendor` varchar(255) DEFAULT NULL,
  CHANGE `product.tags` `product_tags` varchar(255) DEFAULT NULL,
  CHANGE `product.featured` `product_featured` tinyint(1) NOT NULL DEFAULT 0,
  CHANGE `product.featured.location` `product_featured_location` varchar(255) DEFAULT NULL,
  CHANGE `product.featured.carousel` `product_featured_carousel` tinyint(1) NOT NULL DEFAULT 0,
  CHANGE `product.featured.image` `product_featured_image` varchar(255) DEFAULT NULL,
  ADD KEY `product_category` (`product_category`);

ALTER TABLE `shop_cart`
  CHANGE `cart.id` `cart_id` varchar(255) NOT NULL,
  CHANGE `cart.created` `cart_created` int(11) NOT NULL DEFAULT 0,
  CHANGE `cart.product` `cart_product` varchar(255) NOT NULL,
  CHANGE `cart.product.id` `cart_product_id` int(11) NOT NULL,
  CHANGE `cart.qty` `cart_qty` int(11) NOT NULL,
  ADD `cart_price` decimal(10,2) DEFAULT NULL AFTER `cart_qty`,
  CHANGE `cart.ip` `cart_ip` varchar(255) NOT NULL,
  CHANGE `cart.stage` `cart_stage` varchar(255) NOT NULL,
  CHANGE `cart.token` `cart_token` varchar(255) NOT NULL,
  CHANGE `cart.processed` `cart_processed` int(11) NOT NULL DEFAULT 0,
  ADD KEY `cart_id` (`cart_id`);

ALTER TABLE `shop_categories`
  CHANGE `category.name` `category_name` varchar(255) NOT NULL,
  CHANGE `category.sub` `category_sub` varchar(255) NOT NULL DEFAULT '',
  CHANGE `category.order` `category_order` int(11) NOT NULL DEFAULT 0,
  ADD KEY `category_name` (`category_name`);

ALTER TABLE `shop_orders`
  CHANGE `order.id` `order_id` varchar(255) NOT NULL,
  CHANGE `order.cart.id` `order_cart_id` varchar(255) NOT NULL,
  CHANGE `order.mollie.id` `order_mollie_id` varchar(255) NOT NULL,
  CHANGE `order.firstname` `order_firstname` varchar(255) NOT NULL,
  CHANGE `order.lastname` `order_lastname` varchar(255) NOT NULL,
  CHANGE `order.address` `order_address` varchar(255) NOT NULL,
  CHANGE `order.city` `order_city` varchar(255) NOT NULL,
  CHANGE `order.zip` `order_zip` varchar(40) NOT NULL,
  CHANGE `order.state` `order_state` varchar(40) NOT NULL,
  CHANGE `order.country` `order_country` varchar(255) NOT NULL,
  CHANGE `order.email` `order_email` varchar(255) NOT NULL,
  CHANGE `order.phone` `order_phone` varchar(255) NOT NULL DEFAULT '',
  CHANGE `order.status` `order_status` varchar(255) NOT NULL,
  CHANGE `order.paid` `order_paid` int(11) NOT NULL DEFAULT 0,
  CHANGE `order.shipping.price` `order_shipping_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  CHANGE `order.total` `order_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  CHANGE `order.date` `order_date` int(11) NOT NULL DEFAULT 0,
  CHANGE `order.shipped` `order_shipped` int(11) NOT NULL DEFAULT 0,
  CHANGE `order.fulfilled` `order_fulfilled` int(11) NOT NULL DEFAULT 0,
  CHANGE `order.token` `order_token` varchar(255) NOT NULL,
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_cart_id` (`order_cart_id`),
  ADD KEY `order_mollie_id` (`order_mollie_id`);

ALTER TABLE `shop_settings`
  CHANGE `settings.email` `settings_email` varchar(255) NOT NULL,
  CHANGE `settings.paypal` `settings_paypal` varchar(255) NOT NULL,
  CHANGE `settings.currency` `settings_currency` varchar(11) NOT NULL,
  CHANGE `settings.country.code` `settings_country_code` varchar(4) NOT NULL,
  CHANGE `settings.tax` `settings_tax` varchar(40) NOT NULL,
  CHANGE `settings.free` `settings_free` decimal(10,2) NOT NULL DEFAULT 0.00,
  CHANGE `settings.shipping` `settings_shipping` decimal(10,2) NOT NULL DEFAULT 0.00,
  CHANGE `settings.announcement` `settings_announcement` varchar(255) NOT NULL,
  CHANGE `settings.mollie.api` `settings_mollie_api` varchar(255) NOT NULL,
  CHANGE `settings.theme` `settings_theme` varchar(255) NOT NULL DEFAULT 'black';

ALTER TABLE `components`
  MODIFY `component_image` varchar(255) NOT NULL DEFAULT '',
  ADD KEY `pid` (`pid`);

ALTER TABLE `pages`
  ADD KEY `page_name` (`page_name`);

-- The time of the last wrong password, so a locked login unlocks by itself.
ALTER TABLE `users`
  MODIFY `attempts` int(11) NOT NULL DEFAULT 0,
  ADD `attempt_time` int(11) NOT NULL DEFAULT 0 AFTER `attempts`,
  ADD UNIQUE KEY `username` (`username`);

-- 4. Text used to be stored HTML-encoded (&amp; for &). It is now stored as typed 
--    and encoded when it is shown, so the stored text is decoded once. Older versions 
--    also dropped the semicolon of an encoded character (&amp Co), which is repaired too.

UPDATE `shop` SET
  `product_title` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`product_title`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `product_description` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`product_description`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `product_category` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`product_category`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `product_catno` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`product_catno`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `product_format` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`product_format`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `product_type` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`product_type`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `product_weight` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`product_weight`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `product_condition` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`product_condition`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `product_ean` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`product_ean`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&');

UPDATE `shop_categories` SET
  `category_name` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`category_name`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `meta_title` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`meta_title`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `meta_description` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`meta_description`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `meta_tags` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`meta_tags`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&');

UPDATE `pages` SET
  `page_name` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`page_name`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `meta_title` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`meta_title`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `meta_description` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`meta_description`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `meta_tags` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`meta_tags`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&');

UPDATE `shop_settings` SET
  `settings_announcement` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`settings_announcement`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `settings_email` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`settings_email`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `settings_currency` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`settings_currency`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `settings_country_code` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`settings_country_code`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `settings_mollie_api` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`settings_mollie_api`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&');

UPDATE `shop_orders` SET
  `order_firstname` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`order_firstname`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `order_lastname` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`order_lastname`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `order_address` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`order_address`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `order_city` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`order_city`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `order_zip` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`order_zip`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `order_state` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`order_state`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `order_country` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`order_country`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&'),
  `order_email` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`order_email`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&');

UPDATE `shop_cart` SET
  `cart_product` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`cart_product`,'&amp;',CHAR(1)),'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&lt','<'),'&gt','>'),'&quot','"'),'&#039',''''),'&amp','&'),CHAR(1),'&');

UPDATE `components` SET
  `component_title` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`component_title`,'&lt;','<'),'&gt;','>'),'&quot;','"'),'&#039;',''''),'&amp;','&');
