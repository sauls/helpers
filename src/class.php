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

use Sauls\Component\Helper\Operation\ClassOperation;
use Sauls\Component\Helper\Operation\Factory\OperationFactory;

function class_traits(string $class): array
{
    return OperationFactory::create(ClassOperation\Traits::class)->execute($class);
}

function class_uses_trait(string $class, string $traitClass): bool
{
    return OperationFactory::create(ClassOperation\UsesTrait::class)->execute($class, $traitClass);
}

function class_ucnp(string $class): string
{
    return OperationFactory::create(ClassOperation\Ucnp::class)->execute($class);
}
