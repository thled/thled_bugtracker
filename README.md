# THlEd BUGTRACKER

[![Version][version-badge]][changelog] [![MIT License][license-badge]][license]

## Requirements

### Server

- PHP 7.4.1 or higher
- MariaDB 10.4.11 or higher
- PHP extensions:
  - Ctype
  - iconv
  - JSON
  - PCRE
  - Session
  - SimpleXML
  - Tokenizer

### Development

- [Docker][docker]
- [Docker-Compose][docker-compose]
- Free ports on host as defined in `.env`

## Installation

1. Clone this repository: `$ git clone git@github.com:thled/thled_bugtracker.git`
1. Change to project directory: `$ cd thled_bugtracker`
1. Copy .env for Docker-Compose: `$ cp .env.dist .env`
1. Build and start the docker containers: `$ docker-compose up -d`
1. Copy .env for App: `$ cp app/.env.docker app/.env.local`
1. Install dependencies: `$ docker-compose exec app composer install`
1. Create DB schema: `$ docker-compose exec app bin/console doctrine:migrations:migrate -n`
1. Load fixtures: `$ docker-compose exec app bin/console doctrine:fixtures:load -n`

## Usage

- Access the application: `localhost:80`
- SSH into container: `$ docker-compose exec app bash`
- Manage DB with Adminer: `localhost:8080`
  - System: MySQL
  - Server: db
  - Username: root
  - Password: root
  - Database: db
- Debug with xDebug:
  - Maybe adjust local IP of host with `xdebug.remote_host` in `docker/php/xdebug.ini`
  - PHPStorm configuration:
    - Change debug port under `Settings/Languages/PHP/Debug` to 9001
    - Add new server under `Settings/Languages/PHP/Servers`
      - Name: docker-server
      - Host: localhost
      - Port: 80
      - Debugger: Xdebug
      - Select "Use path mappings" and map "Project files" to `/usr/src/app`
  - Start "Listening for PHP Debug Connections"
- Receive mails with Mailcatcher: `http://localhost:1080`

## Tests

- Run whole test suite: `$ docker-compose exec app bin/phpunit`

## Contribute

Please do contribute! Issues and pull requests are welcome.

[version-badge]: https://img.shields.io/badge/version-0.4.0-blue.svg
[changelog]: ./CHANGELOG.md
[license-badge]: https://img.shields.io/badge/license-MIT-blue.svg
[license]: ./LICENSE
[docker]: https://docs.docker.com/install/
[docker-compose]: https://docs.docker.com/compose/install/
