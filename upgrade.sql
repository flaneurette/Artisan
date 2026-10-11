-- Upgrade an existing Artisan database to the current schema.
-- Run once: mysql artisan < upgrade.sql

-- Columns that Artisan does not always fill get a default, so that MySQL strict mode
-- (STRICT_TRANS_TABLES) no longer has to be disabled.
ALTER TABLE `components` MODIFY `component_image` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `shop.cart` MODIFY `cart.processed` int(11) NOT NULL DEFAULT 0;
ALTER TABLE `shop.categories` MODIFY `category.sub` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `shop.categories` MODIFY `category.order` int(11) NOT NULL DEFAULT 0;
ALTER TABLE `shop.orders` MODIFY `order.phone` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `shop.orders` MODIFY `order.paid` int(11) NOT NULL DEFAULT 0;
ALTER TABLE `shop.orders` MODIFY `order.shipped` int(11) NOT NULL DEFAULT 0;
ALTER TABLE `shop.orders` MODIFY `order.fulfilled` int(11) NOT NULL DEFAULT 0;

-- The price of each product at the time of the order.
ALTER TABLE `shop.cart` ADD `cart.price` decimal(10,2) DEFAULT NULL AFTER `cart.qty`;

-- The time of the last wrong password, so a locked login unlocks by itself.
ALTER TABLE `users` MODIFY `attempts` int(11) NOT NULL DEFAULT 0;
ALTER TABLE `users` ADD `attempt_time` int(11) NOT NULL DEFAULT 0 AFTER `attempts`;
