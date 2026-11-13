<?php

ini_set('display_errors', 0);

// This must be defined.
define('WEBSITE','https://localhost/artisan/'); 
define('SITE','https://localhost/artisan/dashboard/'); 
define('UPLOAD_DIR', '../../resources/content/');
define('CHARSET', 'utf8');
define('HOST', 'localhost');
define('DATABASE', 'artisan');
define('USERNAME', 'root');
define('PASSWORD', '');
define('MAX_LOGIN_ATTEMPTS', 10);

/*

Security notice:
It is somewhat safer to store this file below the /www/ folder in case PHP shuts down and files become readable.
To do this, edit the paths to this file all PHP pages, and move this file below /www/

# Cryptography

All user data is AES encrypted with a password.

In `dashboard/resources/PHP/Cryptograhpy.php`, the password needs to be changed manually. Look for the line at the top:

private pwd = 'ArtisanIsAShoppingCart'; 

And change the value to whatever you want. 

The idea is, that it is stored in a file for the reason that if the encrypted database is stolen, 
an attacker still needs a key from a file to decrypt it. 
Hence this password is stored in a PHP file.

*/

?>
