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

namespace Sauls\Component\Helper\Operation\TypeOperation\Converter;

class CastToArrayConverter implements ConverterInterface
{
    public function convert($value)
    {
        return (array) $value;
    }

    public function supports($value): bool
    {
        return true;
    }

    public function getType(): string
    {
        return 'array';
    }

    public function getPriority(): int
    {
        return -2048;
    }
}
