# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Security-Advisories from Roave.

### Changed

- Unpack Symfony packs in composer.json and edit version constraints.

## [0.4.0] - 2020-01-15

### Added

- Github Actions for CI/CD (auto. pipeline for running tests and code analysis).
- Changelog (this file) to show the history and progress of the application.
- MIT License file.

### Changed

- Add links to Changelog and License in Readme.
- Gitignore to no longer ignore the application .env.

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

[unreleased]: https://github.com/thled/thled_bugtracker/compare/v0.4.0...HEAD
[0.4.0]: https://github.com/thled/thled_bugtracker/compare/v0.3.0...v0.4.0
[0.3.0]: https://github.com/thled/thled_bugtracker/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/thled/thled_bugtracker/compare/v0.1.1...v0.2.0
[0.1.1]: https://github.com/thled/thled_bugtracker/compare/v0.1.0...v0.1.1
[0.1.0]: https://github.com/thled/thled_bugtracker/releases/tag/v0.1.0
