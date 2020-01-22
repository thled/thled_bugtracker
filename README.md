# THlEd BUGTRACKER

[![Version][version-badge]][changelog]
[![MIT License][license-badge]][license]
[![Quality Gate Status][sonarcloud-quality-gate-badge]](sonarcloud-dashboard)

## Requirements

### Server

- PHP 7.4 or higher
- PostgreSQL 12.1 or higher
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
  - System: PostgreSqQL
  - Server: db
  - Username: postgres
  - Password: postgres
  - Database: postgres
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

## Code Quality

[![Quality Gate Status][sonarcloud-quality-gate-badge]](sonarcloud-dashboard)

[![Reliability Rating][sonarcloud-reliability-badge]](sonarcloud-dashboard):
[![Bugs][sonarcloud-bugs-badge]](sonarcloud-dashboard)

[![Security Rating][sonarcloud-security]](sonarcloud-dashboard):
[![Vulnerabilities][sonarcloud-vulnerabilities]](sonarcloud-dashboard)

[![Maintainability Rating][sonarcloud-maintainability]](sonarcloud-dashboard):
[![Code Smells][sonarcloud-code-smells]](sonarcloud-dashboard),
[![Duplicated Lines (%)][sonarcloud-duplicated-lines]](sonarcloud-dashboard)

To ensure a high quality of the code base different tools are used to analyse, lint and fix code which does not adhere to the standards (PSR, Symfony etc.).
There are manual tools and automatic tools for this purpose. Manual tools should be executed regularly while developing and automatic tools are executed in the Github Actions Workflow (alias Pipeline) or externally (e.g. SonarQube/SonarCloud).

### Manual tools

- php-cs-fixer: `$ composer fix`

### Automatic tools

- PHPStan: `$ composer analyse`
- PHP_CodeSniffer: `$ composer lint`
- SonarCloud (triggered by pushing to Master branch)

## Tests

- Run whole test suite: `$ docker-compose exec app bin/phpunit`

## Contribute

Please do contribute! Issues and pull requests are welcome.

[version-badge]: https://img.shields.io/badge/version-0.4.0-blue.svg
[changelog]: ./CHANGELOG.md
[license-badge]: https://img.shields.io/badge/license-MIT-blue.svg
[license]: ./LICENSE
[sonarcloud-quality-gate-badge]: https://sonarcloud.io/api/project_badges/measure?project=thled_thled_bugtracker&metric=alert_status
[sonarcloud-reliability-badge]: https://sonarcloud.io/api/project_badges/measure?project=thled_thled_bugtracker&metric=reliability_rating
[sonarcloud-bugs-badge]: https://sonarcloud.io/api/project_badges/measure?project=thled_thled_bugtracker&metric=bugs
[sonarcloud-security]: https://sonarcloud.io/api/project_badges/measure?project=thled_thled_bugtracker&metric=security_rating
[sonarcloud-vulnerabilities]: https://sonarcloud.io/api/project_badges/measure?project=thled_thled_bugtracker&metric=vulnerabilities
[sonarcloud-maintainability]: https://sonarcloud.io/api/project_badges/measure?project=thled_thled_bugtracker&metric=sqale_rating
[sonarcloud-code-smells]: https://sonarcloud.io/api/project_badges/measure?project=thled_thled_bugtracker&metric=code_smells
[sonarcloud-duplicated-lines]: https://sonarcloud.io/api/project_badges/measure?project=thled_thled_bugtracker&metric=duplicated_lines_density
[sonarcloud-dashboard]: https://sonarcloud.io/dashboard?id=thled_thled_bugtracker
[docker]: https://docs.docker.com/install/
[docker-compose]: https://docs.docker.com/compose/install/
