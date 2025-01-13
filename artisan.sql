-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 10, 2025 at 11:50 PM
-- Server version: 10.6.18-MariaDB-cll-lve
-- PHP Version: 8.1.26

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
  `component_image` varchar(255) NOT NULL
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
  `product.status` varchar(255) DEFAULT NULL,
  `product.price` varchar(11) DEFAULT NULL,
  `product.stock` int(11) DEFAULT NULL,
  `product.tax` varchar(11) DEFAULT NULL,
  `product.title` varchar(255) DEFAULT NULL,
  `product.description` mediumtext DEFAULT NULL,
  `product.category` varchar(255) DEFAULT NULL,
  `product.category.id` int(11) DEFAULT NULL,
  `product.category.sub` varchar(255) DEFAULT NULL,
  `product.category.sub.id` int(11) DEFAULT NULL,
  `product.image` varchar(255) DEFAULT NULL,
  `product.image.2` varchar(255) DEFAULT NULL,
  `product.image.3` varchar(255) DEFAULT NULL,
  `product.catno` varchar(255) DEFAULT NULL,
  `product.format` varchar(255) DEFAULT NULL,
  `product.type` varchar(255) DEFAULT NULL,
  `product.weight` varchar(255) DEFAULT NULL,
  `product.condition` varchar(255) DEFAULT NULL,
  `product.ean` varchar(255) DEFAULT NULL,
  `product.sku` varchar(255) DEFAULT NULL,
  `product.vendor` varchar(255) DEFAULT NULL,
  `product.tags` varchar(255) DEFAULT NULL,
  `product.featured` varchar(255) DEFAULT NULL,
  `product.featured.location` varchar(255) DEFAULT NULL,
  `product.featured.carousel` varchar(255) DEFAULT NULL,
  `product.featured.image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `shop`
--

INSERT INTO `shop` (`id`, `product.status`, `product.price`, `product.stock`, `product.tax`, `product.title`, `product.description`, `product.category`, `product.category.id`, `product.category.sub`, `product.category.sub.id`, `product.image`, `product.image.2`, `product.image.3`, `product.catno`, `product.format`, `product.type`, `product.weight`, `product.condition`, `product.ean`, `product.sku`, `product.vendor`, `product.tags`, `product.featured`, `product.featured.location`, `product.featured.carousel`, `product.featured.image`) VALUES
(1, NULL, '0.01', 42, NULL, '144 Carat Diamond', 'A beautiful 144 carat diamond, freshly cut by our team... only $1200.', 'Diamonds', NULL, NULL, NULL, '243c6dc588ac-placeholder.png', NULL, NULL, '143523', 'In box', 'Cut Diamond', '455', 'Used', '12343523452', NULL, NULL, NULL, '1', NULL, '1', '621139e2cc2c-New Project.png'),
(2, NULL, '0.1', 12, NULL, 'Zircon', 'A beautiful Zircon diamond', 'Jewelry', NULL, NULL, NULL, '5bc2ab21e31c-placeholder.png', NULL, NULL, '', '', '', '', '', '', NULL, NULL, NULL, '', NULL, '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shop.cart`
--

CREATE TABLE `shop.cart` (
  `id` int(11) NOT NULL,
  `cart.id` varchar(255) NOT NULL,
  `cart.created` varchar(255) NOT NULL,
  `cart.product` varchar(255) NOT NULL,
  `cart.product.id` int(11) NOT NULL,
  `cart.qty` int(11) NOT NULL,
  `cart.ip` varchar(255) NOT NULL,
  `cart.stage` varchar(255) NOT NULL,
  `cart.token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shop.categories`
--

CREATE TABLE `shop.categories` (
  `id` int(11) NOT NULL,
  `category.name` varchar(255) NOT NULL,
  `category.sub` varchar(255) NOT NULL,
  `category.order` int(11) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_tags` varchar(255) NOT NULL,
  `meta_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop.categories`
--

INSERT INTO `shop.categories` (`id`, `category.name`, `category.sub`, `category.order`, `meta_description`, `meta_tags`, `meta_title`) VALUES
(1, 'Diamonds', '', 1, '', '', ''),
(3, 'Rings', '', 3, 'test', 'test,test', 'test'),
(4, 'Jewelry', '', 2, 'Jewelry', 'Jewelry', 'Jewelry');

-- --------------------------------------------------------

--
-- Table structure for table `shop.orders`
--

CREATE TABLE `shop.orders` (
  `id` int(11) NOT NULL,
  `order.id` varchar(255) NOT NULL,
  `order.cart.id` varchar(255) NOT NULL,
  `order.mollie.id` varchar(255) NOT NULL,
  `order.firstname` varchar(255) NOT NULL,
  `order.lastname` varchar(255) NOT NULL,
  `order.address` varchar(255) NOT NULL,
  `order.city` varchar(255) NOT NULL,
  `order.zip` varchar(40) NOT NULL,
  `order.state` varchar(40) NOT NULL,
  `order.country` varchar(255) NOT NULL,
  `order.email` varchar(255) NOT NULL,
  `order.phone` varchar(255) NOT NULL,
  `order.status` varchar(255) NOT NULL,
  `order.paid` int(11) NOT NULL,
  `order.shipping.price` varchar(255) NOT NULL,
  `order.total` varchar(255) NOT NULL,
  `order.date` varchar(255) NOT NULL,
  `order.shipped` int(11) NOT NULL DEFAULT 0,
  `order.fulfilled` int(11) NOT NULL DEFAULT 0,
  `order.token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shop.settings`
--

CREATE TABLE `shop.settings` (
  `id` int(11) NOT NULL,
  `settings.email` varchar(255) NOT NULL,
  `settings.paypal` varchar(255) NOT NULL,
  `settings.currency` varchar(11) NOT NULL,
  `settings.country.code` varchar(4) NOT NULL,
  `settings.tax` varchar(40) NOT NULL,
  `settings.free` varchar(40) NOT NULL,
  `settings.shipping` int(11) NOT NULL,
  `settings.announcement` varchar(255) NOT NULL,
  `settings.mollie.api` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop.settings`
--

INSERT INTO `shop.settings` (`id`, `settings.email`, `settings.paypal`, `settings.currency`, `settings.country.code`, `settings.tax`, `settings.free`, `settings.shipping`, `settings.announcement`, `settings.mollie.api`) VALUES
(1, 'info@example.org', 'info@example.org', '$', 'EUR', '12', '50.00', 15, 'Free shipping above $50, same day delivery!', 'test_dHar4XY7LxsDOtmnkVtjNVWXLSlXsM');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `attempts` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `attempts`) VALUES
(1, 'admin', '$2y$10$ooXjcp5z6QaLr25rFNabiuP.G6i.EE.Ot4phvNrPYe0kmTOO24g0.', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `components`
--
ALTER TABLE `components`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shop.cart`
--
ALTER TABLE `shop.cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shop.categories`
--
ALTER TABLE `shop.categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shop.orders`
--
ALTER TABLE `shop.orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shop.settings`
--
ALTER TABLE `shop.settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `shop.cart`
--
ALTER TABLE `shop.cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shop.categories`
--
ALTER TABLE `shop.categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shop.orders`
--
ALTER TABLE `shop.orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shop.settings`
--
ALTER TABLE `shop.settings`
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
