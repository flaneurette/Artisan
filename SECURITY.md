# Security

Security declaration: This software was built with the highest degree of security in mind, following the OWASP guidelines for security.

### Password
By default the admin password is admin. Upon login, this can be changed under admin settings, and the dashboard shows a warning until it has been changed. The password is stored hashed with bcrypt.

### Login restriction
After 10 wrong passwords the login is locked for 15 minutes, and unlocks by itself. This slows down password bruteforce attempts. The amount of attempts and the lockout time can be changed in configuration.php.

### CSRF / SSRF
All pages are protected against CSRF (cross site request forgery) and only the admin has access to the pages and files

### XSS
Where possible, all pages are protected against XSS, Header forgery and Iframe inclusion. Be mindful, though, as certain basic HTML is allowed in components and the editors. Yet, all other HTML is encoded before display, and where possible, stored as such in the database.

### SQL injection
All data is checked upon database insertion, and properly binded as per the industry standard. 

### Sessions
Session data is protected and checked for any DDos attacks, if detected, a warning will be visible, and it won't be possible to modify data or to proceed further. This prevent overwriting and bruteforcing session data.

### Reporting
If you find a new security flaw, please open up an issue.
