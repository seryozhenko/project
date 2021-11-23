# Description
* PHP 7.4
* Symfony 5.3.11
* MySQL 5.7

# Installing
## Windows
- Windows 10 version 2004 or higher
- Enabled WSL2
- Installed Docker Desktop 19.03.0+ with enabled WSL2 containers
- Installed Ubuntu for Windows 10
- Go to Ubuntu and clone project inside it.
## Linux
Install docker/ docker-compose.

# Run
- docker-compose up -d --force-recreate --build
- docker-compose exec php-fpm composer install
- docker-compose exec php-fpm php bin/console doctrine:migrations:migrate