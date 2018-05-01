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

class StringIn implements StringInInterface
{
    public function execute(string $value, array $values): bool
    {
        foreach ($values as $checkValue) {
            if (false !== stripos($checkValue, $value)) {
                return true;
            }
        }

        return false;
    }
}
