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

namespace Sauls\Component\Helper\Operation\ObjectOperation;

use Sauls\Component\Helper\Exception\ClassPropertyNotSetException;

class SetPropertyValue extends AbstractOperation implements SetPropertyValueInterface
{
    /**
     * @throws ClassPropertyNotSetException
     */
    public function execute(object $object, string $property, $value): void
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
}
