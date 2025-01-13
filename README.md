# Artisan
Artisan is a simple yet complete modern shopping cart software, specifically engineered for Artisans who want to sell their products online. The dashboard is intuitive and easy to use, and also comes with a default Artisan storefront. Artisan is written in PHP and uses a MySQL database architecture.

<img src="https://github.com/flaneurette/Artisan/blob/main/assets/images/demo.png" />

# Installation

0. Download or clone Artisan.
1. Edit dashboard/configuration.php and define the database and paths.
2. Import the Artisan SQL file into MySQL, goto the /dashboard/ and login with: username: admin, password: admin.

   The password can be changed in the admin panel under settings.
3. Change the mollie API key under settings to your Mollie API key.

# Payment

Artisan uses the Mollie payment gateway to process transactions. 

In dashboard/shop-settings, a Mollie API key must be given for this to work. The shop owner must have a Mollie.com account for Artisan to function properly, this includes testing. 
By default, Mollie determines what type of payment method is chosen based upon country code. Payment methods can be changed at Mollie.com


# Requirements
Ideally PHP >= 7.2, MySQL, mod_rewrite, although Artisan was able to run on lower versions of PHP. However, PHP 8 is supported and recommended.

MySQL STRICT_TRANS_TABLES need to be disabled, if not Artisan will not work properly.
