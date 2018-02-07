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

/**
 * @throws PropertyNotAccessibleException
 */
function configure_object(object $object, array $properties, array $methodPrefixes = ['set', 'add']): object
{
    try {
        foreach ($properties as $property => $value) {
            $valueAssigned = false;
            foreach ($methodPrefixes as $setterMethodPrefix) {

                $setterMethod = concat_object_method($setterMethodPrefix, $property);

                if (\method_exists($object, $setterMethod)) {
                    $object->$setterMethod($value);
                    $valueAssigned = true;
                }
            }

            if (false === $valueAssigned) {
                $object->$property = $value;
            }
        }

        return $object;
    } catch (\Throwable $t) {
        throw new PropertyNotAccessibleException($t->getMessage());
    }
}

/**
 * @return null|mixed
 *
 * @throws PropertyNotAccessibleException
 */
function get_object_property_value(object $object, string $property, array $methodPrefixes = ['get', 'is'])
{
    try {
        foreach ($methodPrefixes as $getterMethodPrefix) {
            $getterMethod = concat_object_method($getterMethodPrefix, $property);

            if (\method_exists($object, $getterMethod)) {
                return $object->$getterMethod();
            }
        }

        return $object->$property;
    } catch (\Throwable $t) {
        throw new PropertyNotAccessibleException($t->getMessage());
    }
}

function concat_object_method(string $prefix, string $property): string
{
    return $prefix.ucfirst($property);
}

/**
 * @param mixed $value
 *
 * @throws ClassPropertyNotSetException
 */
function set_object_property_value(object $object, string $property, $value): void
{
    try {
        $reflectionClass = new \ReflectionClass($object);
        $reflectionProperty = $reflectionClass->getProperty($property);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);
    } catch (\Exception $e) {
        throw new ClassPropertyNotSetException(
            sprintf('Failed to set `%s` class `%s` property value.', \get_class($object), $property)
        );
    }
}
