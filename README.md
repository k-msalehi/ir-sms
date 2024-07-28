# IrSms Package

This package provides an interface to interact with Iranian SMS gateways for sending SMS messages using patterns.

## Installation

To install the package, use Composer:

```sh
composer require kmsalehi/ir-sms
```

##Configuration

There is a configuration file in config/ir-sms.php with the following structure:

```php
<?php
return [
    'debug' => true,
    'sms.ir' => [
        'api_key' => 'your_api_key_here',
        'username' => 'your_username_here',
        'password' => 'your_password_here',
    ],
];
```

You can also create a config/ir-sms-local.php file to override the default configuration.