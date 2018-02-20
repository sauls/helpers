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

namespace Sauls\Component\Helper\Operation\DateTimeOperation;

abstract class AbstractOperation
{
    protected function createDateObject($date): \DateTime
    {
        if (\is_string($date)) {
            $date = new \DateTime($date);
        }

        return $date;
    }
}
