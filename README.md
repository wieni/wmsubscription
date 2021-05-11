wmsubscription
======================

[![Latest Stable Version](https://poser.pugx.org/wieni/wmsubscription/v/stable)](https://packagist.org/packages/wieni/wmsubscription)
[![Total Downloads](https://poser.pugx.org/wieni/wmsubscription/downloads)](https://packagist.org/packages/wieni/wmsubscription)
[![License](https://poser.pugx.org/wieni/wmsubscription/license)](https://packagist.org/packages/wieni/wmsubscription)

> Provides a common interface for managing email marketing platform subscriptions.

## Why?
- **Easily create newsletter subscription forms** tailored to your project,
but without the boilerplate
- **Platform-agnostic API** which makes it possible to easily switch 
between providers and to share even more code between projects.

## Installation

This package requires PHP 7.1 and Drupal 8 or higher. It can be
installed using Composer:

```bash
 composer require wieni/wmsubscription
```

## How does it work?
### Choosing a provider
Aside from the core package, you'll also need to install a provider module:
- `wmsubscription_mailchimp`, provides a [MailChimp](https://mailchimp.com) implementation
- `wmsubscription_campaignmonitor`, provides a [Campaign Monitor](https://www.campaignmonitor.com) implementation

After installing a provider module, change the `tool` key in the 
`wmsubscription.settings` config.

### Choosing between direct or queued subscriptions 
When calling external API's, it's often a good idea to queue operations so 
that in case of connection problems, no operations are lost.

By default, subscriptions are handled immediately after calling 
[`SubscriptionToolInterface::addSubscriber`](src/SubscriptionToolInterface.php).
This behaviour can be changed by setting the `manager` key to 
`wmsubscription.manager.queued`. Operations will be added to a queue, 
so make sure cron is executed regularly.

To make sure visitors cannot subscribe the same email address while the 
previous operation is still in queue, this module provides a custom queue 
database implementation checking for duplicates. To enable the custom implementation, add the following snippet to your `settings.php`:
```php
$settings['queue_service_wmsubscription_subscriptions'] = 'wmsubscription.queue.unique_subscription';
```

### Dead Letter Queue integration
This module provides a submodule that can be used to combine the functionality of the 
[Dead Letter Queue module](https://github.com/wieni/dead_letter_queue) with the unique subscription queue:
`wmsubscription_dead_letter_queue`. 

To enable this feature, use the following snippet in your `settings.php` instead of the one mentioned 
above:
```php
$settings['queue_service_wmsubscription_subscriptions'] = 'wmsubscription_dead_letter_queue.queue.database';
```

To add support for the Dead Letter Queue UI module as well, enable the `wmsubscription_dead_letter_queue_ui` module.

## Changelog
All notable changes to this project will be documented in the
[CHANGELOG](CHANGELOG.md) file.

## Security
If you discover any security-related issues, please email
[security@wieni.be](mailto:security@wieni.be) instead of using the issue
tracker.

## License
Distributed under the MIT License. See the [LICENSE](LICENSE) file
for more information.
