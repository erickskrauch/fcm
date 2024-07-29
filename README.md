# Firebase Cloud Messaging PHP Client

Just another client to send push notifications via Firebase Cloud Messaging (FCM). The implementation is inspired by [Paragraph1/php-fcm](https://github.com/Paragraph1/php-fcm) and its fork [guigpm/php-fcm](https://github.com/guigpm/php-fcm), but heavily reworked in favor of simpler code and better implementation.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/erickskrauch/fcm.svg?style=flat-square)](https://packagist.org/packages/erickskrauch/fcm)
[![Build Status](https://img.shields.io/github/actions/workflow/status/erickskrauch/fcm/ci.yml?branch=master&style=flat-square)](https://github.com/erickskrauch/fcm/actions)
[![Changelog](https://img.shields.io/badge/changelog-Keep%20a%20Changelog-%23E05735?style=flat-square)](CHANGELOG.md)
[![PHP version](https://img.shields.io/packagist/dependency-v/erickskrauch/fcm/php?style=flat-square)](composer.json)
[![Software License](https://img.shields.io/badge/license-MIT-green.svg?style=flat-square)](LICENSE)

## Installation

This library relies on [PSR-18](https://www.php-fig.org/psr/psr-18/) which defines how HTTP message should be sent and received. You can use any library to send HTTP messages that implements [psr/http-client-implementation](https://packagist.org/providers/psr/http-client-implementation). Read more about PSR-18 in [this blog](https://www.php-fig.org/blog/2018/11/psr-18-the-php-standard-for-http-clients/).

To install this library with [Guzzle](https://packagist.org/packages/guzzlehttp/guzzle) you may run the following command:

```sh
composer require erickskrauch/fcm guzzlehttp/guzzle
```

Or using the [curl client](https://packagist.org/packages/php-http/curl-client) (you'll need to provide a [PSR7](https://www.php-fig.org/psr/psr-7/) implementation such as [nyholm/psr7](https://packagist.org/packages/nyholm/psr7) if not using Guzzle):

```sh
composer require erickskrauch/fcm php-http/curl-client nyholm/psr7
```

## Usage

The component's constructor allows you to explicitly pass all dependencies. But you can also pass only the mandatory API token, and all other dependencies will be found automatically. The example below relies on [auto discovery](https://docs.php-http.org/en/latest/discovery.html).

```php
use ErickSkrauch\Fcm\Client;
use ErickSkrauch\Fcm\Message\Message;
use ErickSkrauch\Fcm\Message\Notification;
use ErickSkrauch\Fcm\Recipient\Device;

$client = new Client('SERVICE ACCOUNT OAUTH TOKEN', 'project-x146w');

$notification = new Notification(, 'testing body');
$notification->setTitle('Wow, something happened...');
$notification->setBody('It just works!');

$message = new Message();
$message->setNotification($notification);
$message->setCollapseKey('collapse.key');

$recipient = new Device('your-device-token'); // or new ErickSkrauch\Fcm\Recipient\Topic('topic');

$result = $client->send($message, $recipient);
```

## Contribute

This library in an Open Source under the MIT license. It is, thus, maintained by collaborators and contributors.

Feel free to contribute in any way. As an example you may:
* Trying out the `master` code. 
* Create issues if you find problems. 
* Reply to other people's issues.
* Review PRs.

### Ensuring code quality

The project has several tools for quality control. All checks are performed in CI, but if you want to perform checks locally, here are the necessary commands:

```sh
vendor/bin/php-cs-fixer fix -v
vendor/bin/phpstan analyse
vendor/bin/phpunit
vendor/bin/infection # Try "env XDEBUG_MODE=coverage vendor/bin/infection" in case of errors
```
