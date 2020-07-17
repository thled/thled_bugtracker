# THlEd BUGTRACKER

[![Version][version-badge]][changelog]
[![MIT License][license-badge]][license]
[![Symfony][symfony-badge]][symfony]
[![Pipeline][pipeline-badge]][pipeline]
[![Codecov][codecov-badge]][codecov]
[![Quality Gate Status][sonarcloud-quality-gate-badge]][sonarcloud-dashboard]

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
1. Initialize the app: `$ docker-compose exec app composer bootstrap`

## Usage

- Access the application: `localhost:80`
- SSH into container: `$ docker-compose exec app bash`
- SSH into node container for using Yarn: `$ docker-compose exec frontend ash`
- Manage DB with Adminer: `localhost:8080`
  - System: `PostgreSQL`
  - Server: `db`
  - Username: `db`
  - Password: `db`
  - Database: `db`
- Receive mails with Mailcatcher: `http://localhost:1080`
- Debug with xDebug:
  - Maybe adjust local IP of host with `xdebug.remote_host` in `docker/php/xdebug.ini`
  - Vim configuration:
    - Use Vdebug Plugin from `vim-vdebug/vdebug`
    - Copy local rc file: `$ cp app/.nvimrc.dist app/.nvimrc`
    - Change second part of `path_maps` to the project on host in `.nvimrc`
  - PHPStorm configuration:
    - Change debug port under `Settings/Languages/PHP/Debug` to `9001`
    - Add new server under `Settings/Languages/PHP/Servers`
      - Name: `docker-server`
      - Host: `localhost`
      - Port: `80`
      - Debugger: `Xdebug`
      - Select "Use path mappings" and map "Project files" to `/usr/src/app`
  - Start "Listening for PHP Debug Connections"
- Lint with CodeSniffer:
  - PHPStorm configuration:
    - Set path to script under `Settings/Languages/PHP/Quality_Tools/PHP_CodeSniffer` to `path/to/project/app/scripts/phpcs.sh`
    - Activate "PHP_CodeSniffer validation" under `Settings/Editor/Inspections/PHP/Quality_tools`

## Code Quality

[![PHPStan][phpstan-badge]][phpstan] [![Codecov][codecov-badge]][codecov]

Master [![Pipeline][pipeline-badge]][pipeline],
Develop [![Pipeline Develop][pipeline-dev-badge]][pipeline-dev]

[![Quality Gate Status][sonarcloud-quality-gate-badge]][sonarcloud-dashboard]

[![Reliability Rating][sonarcloud-reliability-badge]][sonarcloud-dashboard]:
[![Bugs][sonarcloud-bugs-badge]][sonarcloud-dashboard]

[![Security Rating][sonarcloud-security]][sonarcloud-dashboard]:
[![Vulnerabilities][sonarcloud-vulnerabilities]][sonarcloud-dashboard]

[![Maintainability Rating][sonarcloud-maintainability]][sonarcloud-dashboard]:
[![Code Smells][sonarcloud-code-smells]][sonarcloud-dashboard],
[![Duplicated Lines (%)][sonarcloud-duplicated-lines]][sonarcloud-dashboard]

To ensure a high quality of the code base different tools are used to analyse, lint and fix code
which does not adhere to the standards (PSR, Symfony etc.).
There are manual tools and automatic tools for this purpose.
Manual tools should be executed regularly while developing and
automatic tools are executed in the Github Actions Workflow (alias Pipeline) or externally (e.g. SonarQube/SonarCloud).

### Manual tools

- PHP Code Beautifier and Fixer: `$ composer fix`

### Automatic tools

- PHPStan: `$ composer analyse`
- PHP_CodeSniffer: `$ composer lint`
- SonarCloud (triggered by pushing to Master branch)
- Codecov (triggered by pushing)

## Developing

### Workflow

This project uses a custom workflow named ["THlEd-FLOW"][thled-flow] which is based on ["GitHub flow"][github-flow].

### Tests

#### Usage

- Run whole test suite: `$ composer tests`
- Run unit tests: `$ composer unit`
- Run integration tests: `$ composer integration`
- Run functional tests: `$ composer functional`
- Run code coverage: `$ composer code-coverage` (then open `app/coverage/index.html`)

#### Writing tests

##### Setup

- PHPUnit is used as testing framework.
- Prophecy is used as object mocking framework.
- There are three types of tests:
  - Unit Tests: Testing methods in isolation. (E.g. Service classes)
  - Integration Tests: Testing modules without stubbing/mocking dependencies like a database. (E.g. Repository classes)
  - Functional Tests: Testing frontend behavior. (E.g. Controller classes)

##### Philosophy

- Seek for high code coverage (>90%).
- Every test must have a `@covers` annotation for the class it covers
to not accidentally cover classes which are not under test.
- Code coverage should be reached by Unit Tests.
Exceptions are Controllers and Repositories which are tested by Functional Tests and Integration Tests respectively.
- Do not test private/protected methods explicitly.
- Do not test objects which have no logic like Entities and Models/DTOs.
- Do not test code which will not run in production like DataFixtures.
- It is not necessary to stub/mock third party libraries and objects without logic like the ones mentioned before.

## Contribute

Please do contribute! Issues and pull requests are welcome.

[version-badge]: https://img.shields.io/badge/version-0.9.0-blue.svg
[changelog]: ./CHANGELOG.md
[license-badge]: https://img.shields.io/badge/license-MIT-blue.svg
[license]: ./LICENSE
[symfony-badge]: https://img.shields.io/badge/Symfony-5.0-blue.svg
[symfony]: https://symfony.com/releases/5.0
[pipeline-badge]: https://github.com/thled/thled_bugtracker/workflows/ci-pipeline/badge.svg?branch=master
[pipeline]: https://github.com/thled/thled_bugtracker/actions?query=workflow%3A%22ci-pipeline%22+branch%3Amaster
[pipeline-dev-badge]: https://github.com/thled/thled_bugtracker/workflows/ci-pipeline/badge.svg?branch=develop
[pipeline-dev]: https://github.com/thled/thled_bugtracker/actions?query=workflow%3A%22ci-pipeline%22+branch%3Adevelop
[sonarcloud-quality-gate-badge]: https://sonarcloud.io/api/project_badges/measure?project=thled_thled_bugtracker&metric=alert_status
[sonarcloud-reliability-badge]: https://sonarcloud.io/api/project_badges/measure?project=thled_thled_bugtracker&metric=reliability_rating
[sonarcloud-bugs-badge]: https://sonarcloud.io/api/project_badges/measure?project=thled_thled_bugtracker&metric=bugs
[sonarcloud-security]: https://sonarcloud.io/api/project_badges/measure?project=thled_thled_bugtracker&metric=security_rating
[sonarcloud-vulnerabilities]: https://sonarcloud.io/api/project_badges/measure?project=thled_thled_bugtracker&metric=vulnerabilities
[sonarcloud-maintainability]: https://sonarcloud.io/api/project_badges/measure?project=thled_thled_bugtracker&metric=sqale_rating
[sonarcloud-code-smells]: https://sonarcloud.io/api/project_badges/measure?project=thled_thled_bugtracker&metric=code_smells
[sonarcloud-duplicated-lines]: https://sonarcloud.io/api/project_badges/measure?project=thled_thled_bugtracker&metric=duplicated_lines_density
[sonarcloud-dashboard]: https://sonarcloud.io/dashboard?id=thled_thled_bugtracker
[phpstan-badge]: https://img.shields.io/badge/PHPStan-level%208-brightgreen.svg
[phpstan]: https://github.com/phpstan/phpstan
[codecov-badge]: https://codecov.io/gh/thled/thled_bugtracker/branch/master/graph/badge.svg
[codecov]: https://codecov.io/gh/thled/thled_bugtracker
[docker]: https://docs.docker.com/install/
[docker-compose]: https://docs.docker.com/compose/install/
[thled-flow]: https://gist.github.com/thled/c4c689a279107fcbac2bdee2d27548df
[github-flow]: https://guides.github.com/introduction/flow/
