# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/) and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.3.0] - 2024-07-29
### Added
- Compatibility with FCM HTTP v1 API.

### Changed
- Signature of the `\ErickSkrauch\Fcm\Client` class now requires the `projectId` param.
- The return type of the `\ErickSkrauch\Fcm\Client::send()` method is changed to `string`.
- `\ErickSkrauch\Fcm\Client::send()` now throws `\ErickSkrauch\Fcm\Exception\ErrorResponseException`.

### Removed
- `ErickSkrauch\Fcm\Recipient\MultipleDevices` and `ErickSkrauch\Fcm\Recipient\MultipleTopics` recipients. Iterate over per-device or per-topic to archive the same behavior.
- `ErickSkrauch\Fcm\Response\SendResponse`.

### Fixed
- Deprecation error with nullable arguments for PHP 8.4.

## [0.2.1] - 2022-12-21
### Fixed
- `Message::setMutableContent` now assigns the correct field type.

## [0.2.0] - 2022-12-08
### Added
- `Message::setNotification()` method.

### Changed
- `Notification` is no longer an argument of the `Message` constructor.

## 0.1.0 - 2022-12-08
### Added
- First release

[Unreleased]: https://github.com/erickskrauch/fcm/compare/0.3.0...HEAD
[0.3.0]: https://github.com/elyby/php-code-style/compare/0.2.1...0.3.0
[0.2.1]: https://github.com/elyby/php-code-style/compare/0.2.0...0.2.1
[0.2.0]: https://github.com/elyby/php-code-style/compare/0.1.0...0.2.0
