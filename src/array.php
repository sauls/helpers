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

function array_merge(... $arrays): array
{
    $result = \array_shift($arrays);
    while (!empty($arrays)) {
        $nextArray = \array_shift($arrays);
        foreach ($nextArray as $key => $value) {
            if (\is_int($key)) {
                if (\array_key_exists($key, $result)) {
                    $result[] = $value;
                } else {
                    $result[$key] = $value;
                }
            } elseif (\is_array($value) && isset($result[$key]) && \is_array($result[$key])) {
                $result[$key] = array_merge($result[$key], $value);
            } else {
                $result[$key] = $value;
            }
        }
    }

    return $result;
}

/**
 * @param mixed      $array
 * @param mixed      $key
 * @param null|mixed $default
 *
 * @return null|mixed
 */
function array_get_value($array, $key, $default = null)
{
    if ($key instanceof \Closure) {
        return $key($array, $default);
    }

    if (\is_array($key)) {
        $lastKey = array_pop($key);
        foreach ($key as $keyPart) {
            $array = array_get_value($array, $keyPart);
        }
        $key = $lastKey;
    }

    if (\is_array($array) && (isset($array[$key]) || \array_key_exists($key, $array))) {
        return $array[$key];
    }

    if ((bool)($pos = strrpos($key, '.')) !== false) {
        $array = array_get_value($array, substr($key, 0, $pos), $default);
        $key = substr($key, $pos + 1);
    }

    if (\is_object($array)) {
        return get_object_property_value($array, $key) ?? $default;
    }

    if (\is_array($array)) {
        return (isset($array[$key]) || \array_key_exists($key, $array)) ? $array[$key] : $default;
    }

    return $default;
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

        if (!isset($array[$key])) {
            $array[$key] = [];
        }

        if (!\is_array($array[$key])) {
            $array[$key] = [$array[$key]];
        }

        $array = &$array[$key];
    }

    $array[\array_shift($keys)] = $value;
}

function array_remove_key(&$array, $key, $default = null)
{
    $keys = \is_array($key) ? $key : \explode('.', $key);

    while (\count($keys) > 1) {
        $key = \array_shift($keys);

        if (\is_array($array[$key])) {
            $array = &$array[$key];
        }
    }

    $key = \array_shift($keys);

    if (\is_array($array) && (isset($array[$key]) || \array_key_exists($key, $array))) {
        $value = $array[$key];
        unset($array[$key]);

        return $value;
    }

    return $default;
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

function array_key_exists(array $array, $key): bool
{
    $keys = \is_array($key) ? $key : \explode('.', $key);

    while (\count($keys) > 1) {
        $key = \array_shift($keys);

        if (\is_array($array[$key])) {
            $array = &$array[$key];
        }
    }

    $key = \array_shift($keys);

    return isset($array[$key]) || \array_key_exists($key, $array);

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
            $value = $array[$key];
            if (\is_scalar($value)) {
                $result[] = $value;
            } elseif (\is_array($value)) {
                $result = array_merge($result, array_flatten($value));
            } elseif (\is_object($value)) {
                $result[] = (string)$value;
            }
        }

        return \array_values(\array_unique($result));
    } catch (\Throwable $t) {
        throw new \RuntimeException($t->getMessage());
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
