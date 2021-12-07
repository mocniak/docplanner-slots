# Slots App Symfony Boilerplate

This repository is based on PHP 8 and Symfony 5

## Installation

The project is dockerized and configured to work with docker-compose

- to run the container, use `docker-compose up -d`
- get in container, use `bin/console secrets:set DOCPLANNER_API_PASSWORD`
  and `bin/console secrets:set DOCPLANNER_API_USERNAME` to set supplier API credentials
- after a while, the app should be accessible on `http://localhost:3160`

## Tests

- behat: `APP_ENV=test vendor/bin/behat` in php container
- unit: `vendor/bin/phpunit` in php container

## TODOs

All mentioned in the code and also:

- unit tests for services
- make endpoint documentation with OpenAPI/Swagger
- configure code style checker with PHPCS
- enforce `declare(strict_types=1);` in code style
- configure static code analysis with PHPStan
