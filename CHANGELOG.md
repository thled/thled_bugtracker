# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Update to Symfony 5.1.
- Additional PHPStan rules from Ergebnis.
- Project management (list/create/edit).

## [0.9.0] - 2020-04-09

### Added

- "Overview of Bugs" page with list of all Bugs.
- "Developing" section in Readme with link to THlED-FLOW as workflow.
- Editing of Bugs.

### Changed

- Codecov configuration to force 90% coverage and not more than 1% coverage loss per PR.
- Workflow to use THlEd-FLOW and therefor remove Develop branch.

## [0.8.0] - 2020-03-18

### Added

- Roles for authorization.
- Dynamic highlighting of navigation items.
- Code coverage with report functionality from Codecov.
- Bug and related Project, Comment entities.
- "Issue a Bug" page with form for adding new Bugs.
- Tests for several classes.
- Feature Flag for HTML5 validation in forms.

### Changed

- Main frontend style.
- Copyright year to update automatically.
- Entities to use UUID instead of incremental ID.
- Forms use DTOs instead of entities.

## [0.7.0] - 2020-02-06

### Added

- Frontend dependencies like Encore to manage CSS and JS.
- Bootstrap for styling the frontend.
- Style for Login form.
- Style for Register form.
- Translations for future multilingual support.

### Fixed

- Links in Readme.

## [0.6.0] - 2020-01-31

### Added

- Software Requirements Specification as guideline to what to develop.
- EditorConfig for app created with PHPStorm.
- User Fixtures for test users.
- SlevomatCodingStandard rule set to PHP_CodeSniffer and excluded/configured rules.
- Login Form to authenticate users.
- Register Form for letting users register by themselves.
- Add a Composer script for bootstrapping the app.

### Changed

- "Code Quality" section in Readme.
- Add SonarCloud Quality Gate Status to Readme.
- PHPUnit configuration.
- Logging strategy to rotating files.
- DB from MariaDB to PostgreSQL.
- Composer "fix" script now uses PHP Code Beautifier and Fixer instead of PHP CS Fixer.
- Readme with how to use CodeSniffer in PHPStorm through docker container.

### Removed

- PHP Coding Standards Fixer (PHP CS Fixer) tool because it is replaced by PHP Code Beautifier and Fixer.

## [0.5.0] - 2020-01-17

### Added

- Security-Advisories from Roave.
- PHP_CodeSniffer for linting code and ensure code quality in Github Actions.
- PHP CS Fixer to automatically fix code to follow standards.
- SonarCloud for continuous code quality (<https://sonarcloud.io>).

### Changed

- Unpack Symfony packs in composer.json and edit version constraints.
- Add "Code Quality" section to Readme.

## [0.4.0] - 2020-01-15

### Added

- Github Actions Workflow for CI/CD (auto. pipeline for running tests and code analysis).
- Changelog (this file) to show the history and progress of the application.
- MIT License file.

### Changed

- Add links to Changelog and License in Readme.

### Fixed

- Gitignore to no longer ignore the application's .env.

## [0.3.0] - 2020-01-14

### Added

- PHPStan for static code analysis.
- Test controller which can be accessed on "/test".

## [0.2.0] - 2020-01-09

### Added

- Create Symfony Application.

## [0.1.1] - 2020-01-09

### Fixed

- xDebug configuration in the Docker environment.

## [0.1.0] - 2020-01-08

### Added

- Set up the Docker environment for development.
- Readme for installation instructions.

[unreleased]: https://github.com/thled/thled_bugtracker/compare/v0.9.0...HEAD
[0.9.0]: https://github.com/thled/thled_bugtracker/compare/v0.8.0...v0.9.0
[0.8.0]: https://github.com/thled/thled_bugtracker/compare/v0.7.0...v0.8.0
[0.7.0]: https://github.com/thled/thled_bugtracker/compare/v0.6.0...v0.7.0
[0.6.0]: https://github.com/thled/thled_bugtracker/compare/v0.5.0...v0.6.0
[0.5.0]: https://github.com/thled/thled_bugtracker/compare/v0.4.0...v0.5.0
[0.4.0]: https://github.com/thled/thled_bugtracker/compare/v0.3.0...v0.4.0
[0.3.0]: https://github.com/thled/thled_bugtracker/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/thled/thled_bugtracker/compare/v0.1.1...v0.2.0
[0.1.1]: https://github.com/thled/thled_bugtracker/compare/v0.1.0...v0.1.1
[0.1.0]: https://github.com/thled/thled_bugtracker/releases/tag/v0.1.0
