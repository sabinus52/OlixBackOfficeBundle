# OlixBackOffice Bundle for Symfony

## Introduction

### Minimum requirements
- Symfony 6.4
- PHP > 8.2
- Twig 3.0

### Features

- Back Office with custom sidebar and navbar
- User manager
- User profile
- Form DatetimePicker
- Form select2
- Form autocomplete
- Modal

## Installation

Add in the **composer.json** for automation configuration via the Symfony Flex Composer plugin :

~~~ json
    "extra": {
        "symfony": {
            "endpoint": [
                "https://api.github.com/repos/sabinus52/symfony-recipes/contents/index.json",
                "flex://defaults"
            ]
        }
    },
~~~

Install the bundle
~~~ bash
composer require sabinus52/backoffice-bundle
~~~

Install the assets
~~~ bash
./bin/console importmap:require olix-backoffice
~~~

Enjoy !


## Documentation

- [Documentation](docs/README.md) - How to install, use and enjoy this bundle
