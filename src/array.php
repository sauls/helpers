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
 * @param mixed $array
 * @param mixed $key
 * @param null  $default
 *
 * @return mixed|null
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

    if (($pos = strrpos($key, '.')) !== false) {
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
