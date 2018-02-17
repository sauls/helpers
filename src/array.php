<?php
/**
 * This file is part of the sauls/helpers package.
 *
 * @author    Saulius Vaičeliūnas <vaiceliunas@inbox.lt>
 * @link      http://saulius.vaiceliunas.lt
 * @copyright 2018
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sauls\Component\Helper;

use Sauls\Component\Helper\Exception\PropertyNotAccessibleException;

function array_merge(... $arrays): array
{
    $result = \array_shift($arrays);
    while (!empty($arrays)) {
        $nextArray = \array_shift($arrays);
        array_merge_with_next_array($nextArray, $result);
    }

    return $result;
}

function array_merge_with_next_array($nextArray, &$result): void
{
    foreach ($nextArray as $key => $value) {
        array_merge_with_current_array_values($key, $value, $result);
    }
}

function array_merge_with_current_array_values($key, $value, &$result): void
{
    if (\is_int($key)) {
        array_merge_integer_keyed_value($key, $value, $result);
    } elseif (can_merge_two_value_arrays($key, $value, $result)) {
        $result[$key] = array_merge($result[$key], $value);
    } else {
        $result[$key] = $value;
    }
}

function can_merge_two_value_arrays($key, $value, $result): bool
{
    return \is_array($value) && isset($result[$key]) && \is_array($result[$key]);
}

function array_merge_integer_keyed_value($key, $value, &$result): void
{
    if (\array_key_exists($key, $result)) {
        $result[] = $value;
    } else {
        $result[$key] = $value;
    }
}

/**
 * @throws PropertyNotAccessibleException
 */
function array_get_value($array, $key, $default = null)
{
    if (\is_callable($key)) {
        return $key($array, $default);
    }

    return array_get_value_from_array_key_path($array, $key, $default);
}

/**
 * @throws PropertyNotAccessibleException
 *
 */
function array_get_value_from_array_key_path($array, $key, $default)
{
    if (\is_array($key)) {
        $lastKey = array_pop($key);
        foreach ($key as $keyPart) {
            $array = array_get_value($array, $keyPart);
        }
        $key = $lastKey;
    }

    if (is_array_and_key_exists($array, $key)) {
        return $array[$key];
    }

    return array_get_value_from_array_string_key_path($array, $key, $default);
}

/**
 * @throws PropertyNotAccessibleException
 */
function array_get_value_from_array_string_key_path($array, $key, $default)
{
    if ((bool)($pos = strrpos($key, '.')) !== false) {
        $array = array_get_value($array, substr($key, 0, $pos), $default);
        $key = substr($key, $pos + 1);
    }

    if (\is_object($array)) {
        return get_object_property_value($array, $key) ?? $default;
    }

    if (\is_array($array)) {
        return get_array_value_or_fallback_to_default($array, $key, $default);
    }

    return $default;
}

function get_array_value_or_fallback_to_default($array, $key, $default)
{
    return (isset($array[$key]) || \array_key_exists($key, $array)) ? $array[$key] : $default;
}

function is_array_and_key_exists($array, $key): bool
{
    return \is_array($array) && (isset($array[$key]) || \array_key_exists($key, $array));
}

/**
 * @param mixed $path
 * @param mixed $value
 *
 * @return mixed
 */
function array_set_value(array &$array, $path, $value)
{
    if ($path === null) {
        $array = $value;

        return;
    }
    $keys = \is_array($path) ? $path : \explode('.', $path);
    while (\count($keys) > 1) {
        $key = \array_shift($keys);

        array_create_value($array, $key);

        $array = &$array[$key];
    }

    $array[\array_shift($keys)] = $value;
}

function array_create_value(array &$array, $key): void
{
    if (!isset($array[$key])) {
        $array[$key] = [];
    }

    if (!\is_array($array[$key])) {
        $array[$key] = [$array[$key]];
    }
}

function array_remove_key(&$array, $key, $default = null)
{
    $array = &array_remove_shift_key_array_value($array, $key);

    if (can_remove_array_key($array, $key)) {
        return array_unset_key($array, $key);
    }

    return $default;
}

function &array_remove_shift_key_array_value(&$array, &$key): array
{
    $keys = parse_array_key_path($key);
    while (\count($keys) > 1) {
        $key = \array_shift($keys);

        if (\is_array($array[$key])) {
            $array = &$array[$key];
        }
    }

    $key = \array_shift($keys);

    return $array;
}

function array_unset_key(&$array, $key)
{
    $value = $array[$key];
    unset($array[$key]);

    return $value;
}

function can_remove_array_key($array, $key): bool
{
    return \is_array($array) && (isset($array[$key]) || \array_key_exists($key, $array));
}

function array_key_exists(array $array, $key): bool
{
    $keys = parse_array_key_path($key);

    while (\count($keys) > 1) {
        $key = \array_shift($keys);

        if (array_key_isset_and_is_array($array, $key)) {
            $array = &$array[$key];
        }
    }

    $key = \array_shift($keys);

    return isset($array[$key]) || \array_key_exists($key, $array);
}

function array_string_key_exists(array $array, $key): bool
{
    return \is_string($key) && \array_key_exists($key, $array);
}

function array_key_isset_and_is_array(array $array, $key): bool
{
    return isset($array[$key]) && \is_array($array[$key]);
}

/**
 * @param mixed|string|array $key
 */
function parse_array_key_path($key): array
{
    return \is_array($key) ? $key : \explode('.', $key);
}

function array_remove_value(&$array, $value): array
{
    $result = [];
    if (\is_array($array)) {
        foreach ($array as $key => $val) {
            if ($val === $value) {
                $result[$key] = $val;
                unset($array[$key]);
            }
        }
    }

    return $result;
}

function array_deep_search(array $array, $searchValue)
{
    $result = [];
    foreach ($array as $key => $value) {
        if ($path = array_deep_search_value($key, $value, $searchValue)) {
            $result[] = $path;
        }
    }

    return $result;
}

function array_deep_search_value($key, $value, $searchValue, $path = [])
{
    if (\is_array($value) && $subPath = array_deep_search($value, $searchValue)) {
        return array_flatten(array_merge($path, [$key], $subPath));
    }

    if ($value === $searchValue) {
        return [$key];
    }

    return [];
}

/**
 * @throws \RuntimeException
 */
function array_flatten(array $array): array
{
    try {
        $result = [];
        foreach (\array_keys($array) as $key) {
            array_flatten_value($array[$key], $result);
        }

        return \array_values(\array_unique($result));
    } catch (\Throwable $t) {
        throw new \RuntimeException($t->getMessage());
    }
}

/**
 * @param mixed $value
 */
function array_flatten_value($value, array &$result): void
{
    if (\is_scalar($value)) {
        $result[] = $value;
    }

    if (\is_array($value)) {
        $result = array_merge($result, array_flatten($value));
    }

    if (\is_object($value)) {
        $result[] = (string)$value;
    }
}

function array_multiple_keys_exists(array $array, array $keys): bool
{
    if (empty($keys)) {
        return false;
    }

    $result = true;

    foreach ($keys as $key) {
        $result &= array_key_exists($array, $key);
    }

    return $result ? true : false;
}

function array_keys(array $array, string $prefix = '')
{
    $result = [];

    foreach ($array as $key => $value) {
        if (\is_array($value) && !empty($value)) {
            $result = array_merge($result, array_keys($value, $prefix.$key.'.'));
        } else {
            $result[] = $prefix.$key;
        }
    }

    return $result;
}
