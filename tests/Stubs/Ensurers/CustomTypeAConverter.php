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

namespace Sauls\Component\Helper\Stubs\Ensurers;

use Sauls\Component\Helper\Operation\TypeOperation\Converter\ConverterInterface;
use Sauls\Component\Helper\Stubs\CustomInterface;

class CustomTypeAConverter implements ConverterInterface
{
    /**
     * @param CustomInterface $value
     */
    public function convert($value): string
    {
        return $value->toCustom();
    }

    public function supports($value): bool
    {
        return is_subclass_of($value, CustomInterface::class);
    }

    public function getType(): string
    {
        return 'custom';
    }

    public function getPriority(): int
    {
        return 32;
    }
}
