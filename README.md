# nhtsa
This is a REST API developed in Lumen framework written in PHP.<br/>
It returns vehicles crash ratings by querying nhtsa open URL. 

INSTALL:
-------
- Clone the reposiory<br>
- Optionally copy .env.example to .env and set APP_DEBUG=true|false as per requirement.<br>
- Run composer install. <br>
- Try with PHP built in standalone web server(php -S localhost:8080 public/index.php) or point apache webroot to public/ directory. <br>
- If deploying with Apache, make sure apache uses PHP version 7.1 or above.

