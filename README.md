# Nova Boolean Group

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stepanenko3/nova-boolean-group.svg?style=flat-square)](https://packagist.org/packages/stepanenko3/nova-boolean-group)
[![Total Downloads](https://img.shields.io/packagist/dt/stepanenko3/nova-boolean-group.svg?style=flat-square)](https://packagist.org/packages/stepanenko3/nova-boolean-group)
[![License](https://poser.pugx.org/stepanenko3/nova-boolean-group/license)](https://packagist.org/packages/stepanenko3/nova-boolean-group)

![screenshot of field](screenshots/field.png)

## Description

Extended BooleanGroup Field for Laravel Nova

## Features

-

## Requirements

- `php: >=8.0`
- `laravel/nova: ^4.0`

## Installation

```bash
# Install the package
composer require stepanenko3/nova-boolean-group
```

Publish the config file:

``` bash
php artisan vendor:publish --provider="Stepanenko3\NovaBooleanGroup\FieldServiceProvider" --tag="config"
```

## Usage

Add the use declaration to your resource and use the fields:

```php
use Stepanenko3\NovaBooleanGroup\BooleanGroup;
...

BooleanGroup::make('Permissions', 'permissions'),
```

## Screenshots

![screenshot of field](screenshots/field-dark.png)
![screenshot of field](screenshots/field-mobile.png)

## Credits

- [Artem Stepanenko](https://github.com/stepanenko3)

## Contributing

Thank you for considering contributing to this package! Please create a pull request with your contributions with detailed explanation of the changes you are proposing.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).
