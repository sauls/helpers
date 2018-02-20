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

namespace Sauls\Component\Helper\Operation\StringOperation;


class Snakeify implements SnakeifyInterface
{
    public function execute(string $value): string
    {
        \preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $value, $matches);
        $result = $matches[0];
        foreach ($result as &$match) {
            $match = $match === \strtoupper($match) ? \strtolower($match) : \lcfirst($match);
        }

        return implode('_', $result);
    }
}
