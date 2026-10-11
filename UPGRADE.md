# Upgrading Artisan

These steps upgrade an Artisan shop that was installed before October 2026 to the current version. The database layout changed in that version, so the files and the database have to be upgraded together.

New installations do not need this file: follow the installation steps in README.md.

## Before you start

- Upgrade at a quiet moment. Customers who are in the middle of a payment may not get their order confirmed, and open shopping carts are emptied once.
- You need access to the files of the shop and to its MySQL or MariaDB database.

## 1. Make a backup

Back up the database:

	mysqldump -u USER -p DATABASE > artisan-backup.sql

Back up the files as well, at least these:

- `dashboard/configuration.php`: your database login and shop address
- `resources/content/`: the images you uploaded
- `assets/images/`: your logo, if you replaced it
- `assets/css/themes/custom/tokens.css`: if you made your own theme
- `.htaccess` and `dashboard/.htaccess`: if you changed them

## 2. Replace the files

Replace the Artisan files with the new version, and put your own `resources/content/` and logo back.

If you copy the new files over the old ones instead of starting with an empty folder, delete these files, which no longer exist:

- `dashboard/core/Cryptography.php`
- `dashboard/core/Sanitize.php`
- `dashboard/core/Message.php`
- `dashboard/core/Debug.php`
- `dashboard/resources/PHP/Class.Session.php`
- `dashboard/resources/PHP/Class.Security.php`

Do not put the `dev/` folder on a live server. It is only used to run Artisan on a development machine.

## 3. Update configuration.php

Use the new `dashboard/configuration.php` and fill in your own values for `WEBSITE`, `SITE`, `HOST`, `DATABASE`, `USERNAME` and `PASSWORD` from your backup.

Three settings are new or changed:

- `CHARSET` is now `utf8mb4`.
- `LOGIN_LOCKOUT_SECONDS`: how long the login stays locked after too many wrong passwords. The default is 900 (15 minutes).
- `SECRET`: a secret key for the encryption functions. Set it to a long random value that is unique to your shop, for example the output of:

		php -r "echo bin2hex(random_bytes(32));"

## 4. Upgrade the database

Run `upgrade.sql` **once** on your database:

	mysql -u USER -p DATABASE < upgrade.sql

What it does:

- Renames the tables and columns that had a dot in their name (`shop.orders` becomes `shop_orders`, `product.price` becomes `product_price`).
- Stores prices, totals and shipping costs as numbers and dates as integers.
- Decodes text that used to be stored HTML-encoded (`&amp;` becomes `&`).
- Adds the columns and indexes the new version needs.

Good to know:

- The script cannot be run twice. If it stops with an error, restore the backup from step 1 before trying again.
- A price or total that is not a number is set to 0. A comma in a price (`12,50`) is read as a decimal point (`12.50`).
- MySQL strict mode (`STRICT_TRANS_TABLES`) no longer has to be disabled. If you turned it off for Artisan, you can turn it back on.

## 5. Check .htaccess

Use the new `.htaccess` and `dashboard/.htaccess`, and redo any changes you made to the old ones. If your shop is not in a folder called `/artisan/`, change that folder name in the `cssimage` lines at the bottom of `.htaccess`, as before.

The new file `site/mollie/.htaccess` keeps the payment library away from visitors. It needs Apache 2.4 or newer.

## 6. Check your theme

The white and black themes work without changes, and there is a new theme, Paper.

If you made your own theme in `assets/css/themes/custom/tokens.css`: that file now only holds the colors, fonts and logo. The other styles moved to `assets/css/shop-base.css`. Start from the new `custom/tokens.css` and copy your own values, one by one, from the `:root { ... }` block of your old file into it. Do not replace the whole block: the new file has a few lines your old one does not have, and those have to stay.

## 7. Check the shop

- Log in to the dashboard. If you still use the password `admin`, the dashboard shows a warning until you change it; a new password needs at least 8 characters.
- Open shop settings and check the Mollie API key, the currency and the shipping costs.
- Open a product and a category in the shop, and check that names with characters such as `&` and `'` look right.
- Put a product in the cart and go through the checkout with a Mollie test key.
- Check in the dashboard that the test order shows up as paid, and that the stock of the product went down.

## What changed for you as shop owner

- **Login:** after 10 wrong passwords the login is locked for 15 minutes and then unlocks by itself. Passwords are compared exactly as typed, so characters such as `&` and `<` now work in a password.
- **Orders:** an order is only confirmed by e-mail when Mollie reports it as paid or pending. Cancelled payments bring the customer back to the cart.
- **Pick list:** shows the price the customer paid. Orders from before the upgrade show the current price of the product.
- **Product links:** every product now has its own name in its link. Old links keep working, because Artisan finds the product by the number in the link.

## If you cannot log in after the upgrade

Set a new password directly in the database. Create a hash:

	php -r "echo password_hash('YOUR NEW PASSWORD', PASSWORD_DEFAULT);"

and store it, together with a reset of the lock:

	UPDATE users SET password = 'THE HASH', attempts = 0, attempt_time = 0 WHERE username = 'admin';
