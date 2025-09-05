# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.5.0] - 2025-09-03
### Added
- Add Drupal 11 support

## [1.4.0] - 2025-09-03
### Added
- Add Drupal 10 support

## [1.3.1] - 2021-05-11
### Added
- Add dead letter queue UI integration

## [1.3.0] - 2021-05-11
### Added
- Add dead letter queue integration
- Add Dutch translation of messages config

## [1.2.0] - 2020-09-14
### Added
- Add Drupal 9 support
- Add events to respond to unsubscribes & subscription updates in the remote tool
- Add getSubscriber method to `SubscriptionToolInterface`
- Add [`phpstan`](https://github.com/phpstan/phpstan) to code style fixers

### Changed
- Update [`wieni/wmcodestyle`](https://github.com/wieni/wmcodestyle)
- Make unique subscription queue optional

## [1.1.0] - 2020-03-23
### Added
- Add label property to annotation
- Add setting forms, permissions & menu items
- Add `AlreadyQueuedException` instead of generic `AlreadySubscribedException`

### Changed
- Refactor queue & change queue name
- Lower `drupal/core` version constraint

### Fixed
- Remove default subscription tool

## [1.0.0] - 2020-03-23
Initial release
