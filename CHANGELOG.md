# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [v1.1.0]
This version is compatible with [SimpleSAMLphp v1.17](https://simplesamlphp.org/docs/1.17/simplesamlphp-changelog)

### Changed
- Code reformatted to PSR-2
- Declare module's class under SimpleSAML\Module namespace

## [v1.0.0]
This version is compatible with [SimpleSAMLphp v1.14](https://simplesamlphp.org/docs/1.14/simplesamlphp-changelog)

### Added
- Client class
  - Retrieves attributes from the GOCDB and adds them to the list of attributes received from the identity provider
