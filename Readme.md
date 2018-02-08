# Sauls Helpers

[![Build Status](https://travis-ci.org/sauls/helpers.svg?branch=master)](https://travis-ci.org/sauls/helpers)
[![Latest Stable Version](https://poser.pugx.org/sauls/helpers/v/stable)](https://packagist.org/packages/sauls/helpers)
[![Total Downloads](https://poser.pugx.org/sauls/helpers/downloads)](https://packagist.org/packages/sauls/helpers)
[![Coverage Status](https://coveralls.io/repos/github/sauls/helpers/badge.svg?branch=master)](https://coveralls.io/github/sauls/helpers?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sauls/helpers/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sauls/helpers/?branch=master)
[![License](https://poser.pugx.org/sauls/helpers/license)](https://packagist.org/packages/sauls/helpers)

Various helper functions to help you in everyday software development.

## Requirements

PHP >= 7.2

## Installation

### Using composer
```bash
$ composer require sauls/helpers
```
### Apppend the composer.json file manually
```json
{
    "require": {
        "sauls/helpers": "^1.0"
    }
}
```

## Documentation

| Group | Functions |
|:--:|:---------:|
| **array** | `array_merge, array_get_value, array_set_value, array_remove_key, array_remove_value, array_key_exists, array_deep_search, array_multiple_keys_exists, array_flatten` |
| **class** | `class_traits, class_uses_trait` |
| **crypt** | `data_encrypt, data_decrypt` |
| **datetime** | `print_elapsed_time_short, print_elapsed_time_long, elapsed_time, countdown` |
| **filesystem** | `rrdir` |
| **object** | `configure_object, get_object_property_value, set_object_property_value` |
| **string** | `camelize, snakeify, multi_explode, base64_url_encode, base64_url_decode, strtr` |

