# Security

Security declaration: This software was built with the highest degree of security in mind, following the OWASP guidelines for security.

### Password
By default the admin password is admin. Upon login, this can be changed under settings. The password is stored encrypted with bcrypt.

### Login restriction
It is not possible to enter a wrong password for more than 10 attempts. After that, the admin page is locked and needs to be unlocked by a database administrator. This prevents password brutforce attempts. The amount of attempts can be changed in configuration.php.

### CSRF
All pages are protected against CSRF (cross site request forgery) and only the admin has access to the pages and files

### XSS
Where possible, all pages are protected against XSS, Header forgery and Iframe inclusion. Be mindful, though, as HTML is allowed in components. Yet, all HTML and possbbile scripts are encoded and stored as such in the database.

### SQL injection
All data is checked upon database insertion, and properly binded as per the industry standard. 

### Sessions
Session data is protected and checked for any DDos attacks, if detected, a warning will be visible, and it won't be possible to modify data or to proceed further. This prevent overwriting and bruteforcing session data.

### Reporting
If you find a new security flaw, please open up an issue.
