# Secret Vote

This is a a damn simple secret voting site. The admin has one password that is hardcoded (for now) and can open and close polls. The users can vote one time. This is enforced using PHP sessions, so they could get around the one vote by using different devices or opening and using and closing incognito.

This came out of my Chapter of Alpha Phi Omega needing an easy secret voting system.

## How to setup

1. Setup a webserver with mysql and php by yourself or some hosted service
2. Run the SETUP_DATABASE.sql script
3. Place the .php files wherever your webserver is looking for files (/var/www/html for apache)
4. Login as an admin by going to [whatever your ip is]/admin.php
5. Start and stop polls as needed
6. Refresh to view results
