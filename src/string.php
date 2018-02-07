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

function string_camelize(string $value): string
{
    return \strtr(\ucwords(\strtr($value, ['_' => ' ', '.' => '_ ', '\\' => '_ '])), [' ' => '']);
}

function string_snakeify(string $value): string
{
    \preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $value, $matches);
    $result = $matches[0];
    foreach ($result as &$match) {
        $match = $match === \strtoupper($match) ? \strtolower($match) : \lcfirst($match);
    }

    return implode('_', $result);
}

function multi_explode(array $delimiters = ['.'], string $value): array
{
    return \explode($delimiters[0], \str_replace($delimiters, $delimiters[0], $value));
}

function base64_url_encode(string $value): string
{
    return \strtr(\base64_encode($value), '+/', '-_');
}

function base64_url_decode(string $value): string
{
    return base64_decode(\strtr($value, '-_', '+/'));
}

function strtr(string $string, array $parameters): string
{
    foreach ($parameters as $key => $value) {
        $string = \str_replace($key, $value, $string);
    }

    return $string;
}


