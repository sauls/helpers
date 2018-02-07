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

function class_traits(string $class, bool $autoload = true): array
{
    $classTraits = [];

    do {
        $classTraits = array_merge(class_uses($class, $autoload), $classTraits);
    } while ($class = get_parent_class($class));

    foreach ($classTraits as $trait => $same) {
        $classTraits = array_merge(class_uses($trait, $autoload), $classTraits);
    }

    return array_unique($classTraits);
}

function class_uses_trait(string $class, string $traitClass): bool
{
    return \in_array($traitClass, class_traits($class));
}
