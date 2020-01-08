# THlEd Bugtracker

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

- [Docker][1]
- [Docker-Compose][2]
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

todo

[1]: https://docs.docker.com/install/
[2]: https://docs.docker.com/compose/install/
