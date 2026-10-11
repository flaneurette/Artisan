-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 15, 2025 at 03:11 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `artisan`
--

-- --------------------------------------------------------

--
-- Table structure for table `components`
--

CREATE TABLE `components` (
  `id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `component_title` tinytext NOT NULL,
  `component_text` longtext NOT NULL,
  `component_image` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `components`
--

INSERT INTO `components` (`id`, `pid`, `component_title`, `component_text`, `component_image`) VALUES
(1, 1, 'Index', '<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque non mauris quis erat tristique tristique. Nullam porta mi id mi laoreet, sed tincidunt sem consectetur. Maecenas interdum efficitur placerat. Praesent ornare dictum molestie. Praesent at scelerisque massa, volutpat vehicula mauris. Etiam ultrices leo vel orci bibendum, eu congue est gravida. Suspendisse pellentesque erat eu mauris laoreet eleifend.</div><div><br></div><div>Integer vulputate enim tortor, a auctor est placerat ut. Nullam ac convallis mi. Cras gravida augue at mi egestas vestibulum. Suspendisse fermentum vehicula ante id finibus. Vivamus tempus egestas nibh at finibus. Morbi libero purus, pellentesque pretium tortor elementum, vehicula vestibulum nibh. Quisque bibendum mi ut convallis placerat. Phasellus ultrices nunc nec aliquam mattis. Curabitur eu elit nisl. Nam et dolor a ante blandit ornare ac at tortor. Etiam quis volutpat dui, non rhoncus dui. Donec a placerat quam. Sed vitae tortor quis ex hendrerit sagittis ut ac tortor. Nulla vitae est varius, finibus dolor in, egestas odio. Cras vel quam in augue finibus tincidunt. Aenean a ligula tempus, auctor urna lobortis, condimentum sapien.</div><div><br></div><div>Quisque euismod felis eu lectus tempor molestie. Donec id eros quis enim sagittis rhoncus quis vel elit. Vestibulum commodo blandit lobortis. Vestibulum interdum nunc diam, sit amet sodales felis aliquam non. Quisque in ipsum orci. Ut molestie dolor sit amet velit iaculis aliquam. Suspendisse ac neque at arcu condimentum mattis id ac dui. Nullam dictum eros lorem, et malesuada dolor feugiat ac. Maecenas tincidunt justo vitae lorem tincidunt tempor. Donec vitae massa ut odio lacinia venenatis.</div><div><br></div><div>Fusce mattis finibus accumsan. Donec mattis dui nulla, a bibendum ipsum rutrum vitae. Etiam finibus ligula eu turpis malesuada, id auctor nulla semper. Integer tristique, tortor at mattis vulputate, orci lectus lacinia mi, ut tristique turpis sapien eget neque. Morbi sit amet dapibus ligula. Pellentesque bibendum ultrices odio, non fringilla lorem bibendum ut. Maecenas accumsan tempus facilisis. Suspendisse vehicula dolor eget tortor elementum, eget pretium mauris rhoncus. In vehicula dui sit amet ultricies fringilla.</div><div><br></div><div>Praesent mollis, est eu pharetra facilisis, nulla ligula accumsan nisi, ut maximus arcu ante eu quam. Vestibulum ut massa porttitor, finibus quam sit amet, dapibus metus. Etiam auctor ante vitae diam fringilla, in vestibulum ex accumsan. Nullam sit amet turpis dignissim justo laoreet tristique. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras tincidunt et nibh sit amet scelerisque. Proin quis eros neque. Pellentesque semper tellus non rutrum rhoncus. Mauris non aliquet arcu. Curabitur dapibus elementum lacus faucibus condimentum. Etiam diam velit, elementum a tempus sit amet, venenatis id sapien.</div>', 'thumb.png'),
(2, 2, 'Contact', '<div>Contact us via e-mail, chat, or telephone.</div><div><br></div><div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque non mauris quis erat tristique tristique. Nullam porta mi id mi laoreet, sed tincidunt sem consectetur. Maecenas interdum efficitur placerat. Praesent ornare dictum molestie. Praesent at scelerisque massa, volutpat vehicula mauris. Etiam ultrices leo vel orci bibendum, eu congue est gravida. Suspendisse pellentesque erat eu mauris laoreet eleifend.</div><div><br></div><div>Integer vulputate enim tortor, a auctor est placerat ut. Nullam ac convallis mi. Cras gravida augue at mi egestas vestibulum. Suspendisse fermentum vehicula ante id finibus. Vivamus tempus egestas nibh at finibus. Morbi libero purus, pellentesque pretium tortor elementum, vehicula vestibulum nibh. Quisque bibendum mi ut convallis placerat. Phasellus ultrices nunc nec aliquam mattis. Curabitur eu elit nisl. Nam et dolor a ante blandit ornare ac at tortor. Etiam quis volutpat dui, non rhoncus dui. Donec a placerat quam. Sed vitae tortor quis ex hendrerit sagittis ut ac tortor. Nulla vitae est varius, finibus dolor in, egestas odio. Cras vel quam in augue finibus tincidunt. Aenean a ligula tempus, auctor urna lobortis, condimentum sapien.</div>', 'thumb.png'),
(3, 3, 'About Us', '<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque non mauris quis erat tristique tristique. Nullam porta mi id mi laoreet, sed tincidunt sem consectetur. Maecenas interdum efficitur placerat. Praesent ornare dictum molestie. Praesent at scelerisque massa, volutpat vehicula mauris. Etiam ultrices leo vel orci bibendum, eu congue est gravida. Suspendisse pellentesque erat eu mauris laoreet eleifend.</div><div><br></div><div>Integer vulputate enim tortor, a auctor est placerat ut. Nullam ac convallis mi. Cras gravida augue at mi egestas vestibulum. Suspendisse fermentum vehicula ante id finibus. Vivamus tempus egestas nibh at finibus. Morbi libero purus, pellentesque pretium tortor elementum, vehicula vestibulum nibh. Quisque bibendum mi ut convallis placerat. Phasellus ultrices nunc nec aliquam mattis. Curabitur eu elit nisl. Nam et dolor a ante blandit ornare ac at tortor. Etiam quis volutpat dui, non rhoncus dui. Donec a placerat quam. Sed vitae tortor quis ex hendrerit sagittis ut ac tortor. Nulla vitae est varius, finibus dolor in, egestas odio. Cras vel quam in augue finibus tincidunt. Aenean a ligula tempus, auctor urna lobortis, condimentum sapien.</div><div><br></div><div>Quisque euismod felis eu lectus tempor molestie. Donec id eros quis enim sagittis rhoncus quis vel elit. Vestibulum commodo blandit lobortis. Vestibulum interdum nunc diam, sit amet sodales felis aliquam non. Quisque in ipsum orci. Ut molestie dolor sit amet velit iaculis aliquam. Suspendisse ac neque at arcu condimentum mattis id ac dui. Nullam dictum eros lorem, et malesuada dolor feugiat ac. Maecenas tincidunt justo vitae lorem tincidunt tempor. Donec vitae massa ut odio lacinia venenatis.</div><div><br></div><div>Fusce mattis finibus accumsan. Donec mattis dui nulla, a bibendum ipsum rutrum vitae. Etiam finibus ligula eu turpis malesuada, id auctor nulla semper. Integer tristique, tortor at mattis vulputate, orci lectus lacinia mi, ut tristique turpis sapien eget neque. Morbi sit amet dapibus ligula. Pellentesque bibendum ultrices odio, non fringilla lorem bibendum ut. Maecenas accumsan tempus facilisis. Suspendisse vehicula dolor eget tortor elementum, eget pretium mauris rhoncus. In vehicula dui sit amet ultricies fringilla.</div><div><br></div><div>Praesent mollis, est eu pharetra facilisis, nulla ligula accumsan nisi, ut maximus arcu ante eu quam. Vestibulum ut massa porttitor, finibus quam sit amet, dapibus metus. Etiam auctor ante vitae diam fringilla, in vestibulum ex accumsan. Nullam sit amet turpis dignissim justo laoreet tristique. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras tincidunt et nibh sit amet scelerisque. Proin quis eros neque. Pellentesque semper tellus non rutrum rhoncus. Mauris non aliquet arcu. Curabitur dapibus elementum lacus faucibus condimentum. Etiam diam velit, elementum a tempus sit amet, venenatis id sapien.</div>', 'placeholder.png');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `sub` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `meta_title` text NOT NULL,
  `meta_description` text NOT NULL,
  `meta_tags` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `page_name`, `sub`, `ordering`, `meta_title`, `meta_description`, `meta_tags`) VALUES
(1, '/index/', 0, 1, 'test', 'this is a test', 'test, test'),
(2, '/contact/', 0, 5, 'Contact us', 'Contact us', 'contact'),
(3, '/about-us/', 0, 2, 'About us', 'About us', 'About us, who we are');

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE `shop` (
  `id` int(11) NOT NULL,
  `product_status` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `product_stock` int(11) NOT NULL DEFAULT 0,
  `product_tax` varchar(11) DEFAULT NULL,
  `product_title` varchar(255) DEFAULT NULL,
  `product_description` mediumtext DEFAULT NULL,
  `product_category` varchar(255) DEFAULT NULL,
  `product_category_id` int(11) DEFAULT NULL,
  `product_category_sub` varchar(255) DEFAULT NULL,
  `product_category_sub_id` int(11) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `product_image_2` varchar(255) DEFAULT NULL,
  `product_image_3` varchar(255) DEFAULT NULL,
  `product_catno` varchar(255) DEFAULT NULL,
  `product_format` varchar(255) DEFAULT NULL,
  `product_type` varchar(255) DEFAULT NULL,
  `product_weight` varchar(255) DEFAULT NULL,
  `product_condition` varchar(255) DEFAULT NULL,
  `product_ean` varchar(255) DEFAULT NULL,
  `product_sku` varchar(255) DEFAULT NULL,
  `product_vendor` varchar(255) DEFAULT NULL,
  `product_tags` varchar(255) DEFAULT NULL,
  `product_featured` tinyint(1) NOT NULL DEFAULT 0,
  `product_featured_location` varchar(255) DEFAULT NULL,
  `product_featured_carousel` tinyint(1) NOT NULL DEFAULT 0,
  `product_featured_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop`
--

INSERT INTO `shop` (`id`, `product_status`, `product_price`, `product_stock`, `product_tax`, `product_title`, `product_description`, `product_category`, `product_category_id`, `product_category_sub`, `product_category_sub_id`, `product_image`, `product_image_2`, `product_image_3`, `product_catno`, `product_format`, `product_type`, `product_weight`, `product_condition`, `product_ean`, `product_sku`, `product_vendor`, `product_tags`, `product_featured`, `product_featured_location`, `product_featured_carousel`, `product_featured_image`) VALUES
(1, NULL, 1200.00, 40, NULL, '144 Carat Diamond', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc dapibus ut arcu in tempor. Aliquam cursus dictum dolor bibendum pulvinar. Donec convallis turpis mi, a scelerisque dui auctor ut. Sed volutpat cursus eros consectetur convallis. Morbi iaculis non dolor eget rutrum. Duis nisi nisi, pretium quis blandit quis, volutpat ultricies dolor. Nam euismod porttitor sem, interdum aliquet velit gravida id. Duis egestas malesuada ullamcorper. Vestibulum luctus, erat at malesuada dignissim, ligula urna ornare libero, vitae elementum ipsum quam sit amet risus.<div><br></div><div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc dapibus ut arcu in tempor. Aliquam cursus dictum dolor bibendum pulvinar. Donec convallis turpis mi, a scelerisque dui auctor ut. Sed volutpat cursus eros consectetur convallis. Morbi iaculis non dolor eget rutrum. Duis nisi nisi, pretium quis blandit quis, volutpat ultricies dolor. Nam euismod porttitor sem, interdum aliquet velit gravida id. Duis egestas malesuada ullamcorper. Vestibulum luctus, erat at malesuada dignissim, ligula urna ornare libero, vitae elementum ipsum quam sit amet risus.</div>', 'Diamonds', NULL, NULL, NULL, '243c6dc588ac-placeholder.png', NULL, NULL, '143523', 'In box', 'Cut Diamond', '455', 'Used', '12343523452', NULL, NULL, NULL, 1, NULL, 1, '621139e2cc2c-New Project.png'),
(2, NULL, 0.10, 6, NULL, 'Zircon', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc dapibus ut arcu in tempor. Aliquam cursus dictum dolor bibendum pulvinar. Donec convallis turpis mi, a scelerisque dui auctor ut. Sed volutpat cursus eros consectetur convallis. Morbi iaculis non dolor eget rutrum. Duis nisi nisi, pretium quis blandit quis, volutpat ultricies dolor. Nam euismod porttitor sem, interdum aliquet velit gravida id. Duis egestas malesuada ullamcorper. Vestibulum luctus, erat at malesuada dignissim, ligula urna ornare libero, vitae elementum ipsum quam sit amet risus.<div><br></div><div>Mauris libero arcu, euismod sit amet justo in, consequat dictum dui. Phasellus sed vulputate turpis, faucibus aliquam urna. Proin at ligula velit. Fusce massa elit, interdum ut iaculis eu, dictum sed odio. Nulla facilisi. Curabitur non bibendum leo, a lobortis risus. Curabitur gravida nunc eu dictum semper. Vestibulum vitae fermentum nibh. Nullam nec urna vitae tortor pulvinar fringilla eu vitae est. Cras eleifend sodales egestas. Nullam sagittis et tortor ut malesuada. Vivamus iaculis, ex ut vehicula facilisis, nisi est scelerisque risus, id convallis neque massa id ante.</div>', 'Jewelry', NULL, NULL, NULL, '5bc2ab21e31c-placeholder.png', NULL, NULL, '', '', '', '', '', '', NULL, NULL, NULL, 0, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shop_cart`
--

CREATE TABLE `shop_cart` (
  `id` int(11) NOT NULL,
  `cart_id` varchar(255) NOT NULL,
  `cart_created` int(11) NOT NULL DEFAULT 0,
  `cart_product` varchar(255) NOT NULL,
  `cart_product_id` int(11) NOT NULL,
  `cart_qty` int(11) NOT NULL,
  `cart_price` decimal(10,2) DEFAULT NULL,
  `cart_ip` varchar(255) NOT NULL,
  `cart_stage` varchar(255) NOT NULL,
  `cart_token` varchar(255) NOT NULL,
  `cart_processed` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shop_categories`
--

CREATE TABLE `shop_categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_sub` varchar(255) NOT NULL DEFAULT '',
  `category_order` int(11) NOT NULL DEFAULT 0,
  `meta_description` varchar(255) NOT NULL,
  `meta_tags` varchar(255) NOT NULL,
  `meta_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop_categories`
--

INSERT INTO `shop_categories` (`id`, `category_name`, `category_sub`, `category_order`, `meta_description`, `meta_tags`, `meta_title`) VALUES
(1, 'Diamonds', '', 1, '', '', ''),
(3, 'Rings', '', 4, 'test', 'test,test', 'test'),
(4, 'Jewelry', '', 2, 'Jewelry', 'Jewelry', 'Jewelry');

-- --------------------------------------------------------

--
-- Table structure for table `shop_orders`
--

CREATE TABLE `shop_orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `order_cart_id` varchar(255) NOT NULL,
  `order_mollie_id` varchar(255) NOT NULL,
  `order_firstname` varchar(255) NOT NULL,
  `order_lastname` varchar(255) NOT NULL,
  `order_address` varchar(255) NOT NULL,
  `order_city` varchar(255) NOT NULL,
  `order_zip` varchar(40) NOT NULL,
  `order_state` varchar(40) NOT NULL,
  `order_country` varchar(255) NOT NULL,
  `order_email` varchar(255) NOT NULL,
  `order_phone` varchar(255) NOT NULL DEFAULT '',
  `order_status` varchar(255) NOT NULL,
  `order_paid` int(11) NOT NULL DEFAULT 0,
  `order_shipping_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `order_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `order_date` int(11) NOT NULL DEFAULT 0,
  `order_shipped` int(11) NOT NULL DEFAULT 0,
  `order_fulfilled` int(11) NOT NULL DEFAULT 0,
  `order_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shop_settings`
--

CREATE TABLE `shop_settings` (
  `id` int(11) NOT NULL,
  `settings_email` varchar(255) NOT NULL,
  `settings_paypal` varchar(255) NOT NULL,
  `settings_currency` varchar(11) NOT NULL,
  `settings_country_code` varchar(4) NOT NULL,
  `settings_tax` varchar(40) NOT NULL,
  `settings_free` decimal(10,2) NOT NULL DEFAULT 0.00,
  `settings_shipping` decimal(10,2) NOT NULL DEFAULT 0.00,
  `settings_announcement` varchar(255) NOT NULL,
  `settings_mollie_api` varchar(255) NOT NULL,
  `settings_theme` varchar(255) NOT NULL DEFAULT 'black'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop_settings`
--

INSERT INTO `shop_settings` (`id`, `settings_email`, `settings_paypal`, `settings_currency`, `settings_country_code`, `settings_tax`, `settings_free`, `settings_shipping`, `settings_announcement`, `settings_mollie_api`, `settings_theme`) VALUES
(1, 'info@example.org', 'info@example.org', '$', 'USD', '12', 50.00, 15.00, 'Free shipping above $50, same day delivery!', 'place your mollie API key here', 'white');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `attempt_time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `attempts`, `attempt_time`) VALUES
(1, 'admin', '$2y$10$ooXjcp5z6QaLr25rFNabiuP.G6i.EE.Ot4phvNrPYe0kmTOO24g0.', 1, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `components`
--
ALTER TABLE `components`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_name` (`page_name`);

--
-- Indexes for table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_category` (`product_category`);

--
-- Indexes for table `shop_cart`
--
ALTER TABLE `shop_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`);

--
-- Indexes for table `shop_categories`
--
ALTER TABLE `shop_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_name` (`category_name`);

--
-- Indexes for table `shop_orders`
--
ALTER TABLE `shop_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_cart_id` (`order_cart_id`),
  ADD KEY `order_mollie_id` (`order_mollie_id`);

--
-- Indexes for table `shop_settings`
--
ALTER TABLE `shop_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `components`
--
ALTER TABLE `components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shop`
--
ALTER TABLE `shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shop_cart`
--
ALTER TABLE `shop_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `shop_categories`
--
ALTER TABLE `shop_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shop_orders`
--
ALTER TABLE `shop_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `shop_settings`
--
ALTER TABLE `shop_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
