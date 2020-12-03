# Sauls Helpers

[![Build Status](https://travis-ci.org/sauls/helpers.svg?branch=master)](https://travis-ci.org/sauls/helpers)
[![Packagist](https://img.shields.io/packagist/v/sauls/helpers.svg)](https://packagist.org/packages/sauls/helpers)
[![Total Downloads](https://img.shields.io/packagist/dt/sauls/helpers.svg)](https://packagist.org/packages/sauls/helpers)
[![Coverage Status](https://img.shields.io/coveralls/github/sauls/helpers.svg)](https://coveralls.io/github/sauls/helpers?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sauls/helpers/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sauls/helpers/?branch=master)
[![License](https://img.shields.io/github/license/sauls/helpers.svg)](https://packagist.org/packages/sauls/helpers)

Various helper functions to help you in everyday software development.

## Requirements

PHP >= 7.4

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
| **array** | `array_merge, array_get_value, array_set_value, array_remove_key, array_key_exists, array_remove_value, array_deep_search, array_flatten, array_multiple_keys_exists, array_keys, array_keys_with_value, array_diff_key, array_key_childs_exist` |
| **class** | `class_traits, class_uses_trait, class_ucnp` |
| **crypt** | `data_encrypt, data_decrypt` |
| **datetime** | `elapsed_time, countdown` |
| **filesystem** | `rrmdir` |
| **object** | `define_object, get_object_property_value, set_object_property_value, object_ucnp, object_to_array` |
| **string** | `string_camelize, string_snakeify, explode_using_multi_delimiters, base64_url_encode, base64_url_decode, truncate, truncate_words, truncate_sentences, truncate_html, truncate_html_words, truncate_html_sentences, count_words, count_sentences, string_in, string_contains` |
| **type**| `convert_to, register_converters` |

