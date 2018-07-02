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

use Sauls\Component\Helper\Exception\ClassPropertyNotSetException;
use Sauls\Component\Helper\Exception\PropertyNotAccessibleException;
use Sauls\Component\Helper\Operation\Factory\OperationFactory;
use Sauls\Component\Helper\Operation\ObjectOperation;

/**
 * @throws PropertyNotAccessibleException
 */
function define_object(object $object, array $properties, array $methodPrefixes = ['set', 'add']): object
{
    return (new ObjectOperation\DefineObject)->execute($object, $properties, $methodPrefixes);
}

/**
 * @throws PropertyNotAccessibleException
 */
function get_object_property_value(object $object, string $property, array $methodPrefixes = ['get', 'is'])
{
    return (new ObjectOperation\GetPropertyValue)->execute($object, $property, $methodPrefixes);
}

/**
 * @throws ClassPropertyNotSetException
 */
function set_object_property_value(object $object, string $property, $value): void
{
    (new ObjectOperation\SetPropertyValue)->execute($object, $property, $value);
}

function object_ufqcn(object $value): string
{
    return OperationFactory::create(ObjectOperation\Ufqcn::class)->execute($value);
}
