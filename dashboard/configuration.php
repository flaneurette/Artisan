<?php

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
define('LOGIN_LOCKOUT_SECONDS', 900); // time a login stays locked after too many wrong passwords.

// Secret key for the encryption functions. Set this to a long random value that is unique 
// to your shop, for example the output of: php -r "echo bin2hex(random_bytes(32));"
define('SECRET', '');

/*

Security notice:
It is somewhat safer to store this file below the /www/ folder in case PHP shuts down and files become readable.
To do this, edit the paths to this file all PHP pages, and move this file below /www/

*/
?>
