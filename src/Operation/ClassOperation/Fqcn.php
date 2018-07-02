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

namespace Sauls\Component\Helper\Operation\ClassOperation;

class Fqcn implements FqcnInterface
{
    public function execute(string $value): string
    {
        if (preg_match('~([^\\\\]+?)(type)?$~i', $value, $matches)) {
            return strtolower(preg_replace(
                ['/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'],
                ['\\1_\\2', '\\1_\\2'],
                $matches[1]
            ));
        }
    }
}
