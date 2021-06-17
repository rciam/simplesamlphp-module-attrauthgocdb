# Changelog
  
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Fixed

- Include SimpleXMLElement` in the filter's class

## [v2.0.1] - 2021-02-20

### Fixed

- Fix namespace for `SimpleSAML\Auth\State`

## [v2.0.0] - 2021-01-22

This version is compatible with [SimpleSAMLphp v1.17](https://simplesamlphp.org/docs/1.17/simplesamlphp-changelog)

### Changed

- Comply to [PSR-4: Autoloader](https://www.php-fig.org/psr/psr-4/) guidelines
- Comply to [PSR-1: Basic Coding Standard](https://www.php-fig.org/psr/psr-1/) guidelines
- Comply to [PSR-12: Extended Coding Style](https://www.php-fig.org/psr/psr-12/) guidelines
- Apply modern array syntax to comply with [SimpleSAMLphp v1.17](https://simplesamlphp.org/docs/stable/simplesamlphp-upgrade-notes-1.17)

## [v1.0.0] - 2020-05-13

This version is compatible with [SimpleSAMLphp v1.14](https://simplesamlphp.org/docs/1.14/simplesamlphp-changelog)

### Added

- Authproc filter `attrauthgocdb:Client` for retrieving attributes from the Grid Configuration Database (GOCDB) and adding them to the list of attributes received from the identity provider.
