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

class ToArray implements ToArrayInterface
{
    /**
     * @var GetPropertyValueInterface
     */
    private $getPropertyValueOperation;

    public function __construct(GetPropertyValueInterface $getPropertyValueOperation)
    {
        $this->getPropertyValueOperation = $getPropertyValueOperation;
    }

    public function execute($object, array $properties = [], bool $recursive = true): array
    {
        if (\is_array($object)) {
            return $this->processArray($object, $properties, $recursive);
        }

        if (\is_object($object)) {
            return $this->processObject($object, $properties, $recursive);
        }

        return [$object];
    }

    private function processArray(array $object, array $properties, bool $recursive): array
    {
        if ($recursive) {
            return $this->processRecursiveArray($object, $properties, $recursive);
        }

        return $object;
    }

    private function processRecursiveArray($object, array $properties, bool $recursive): array
    {
        foreach ($object as $key => $value) {
            if ($this->valueIsArrayOrObject($value)) {
                $object[$key] = $this->execute($value, $properties, $recursive);
            }
        }

        return $object;
    }

    private function processObject($object, array $properties, bool $recursive): array
    {
        if (!empty($properties)) {
            $className = \get_class($object);
            if (!empty($properties[$className])) {
                return $this->processProperties($object, $className, $properties, $recursive);
            }
        }

        return $this->processObjectProperties($object, $properties, $recursive);
    }

    private function processProperties($object, string $className, array $properties, bool $recursive): array
    {
        $result = [];
        foreach ($properties[$className] as $key => $name) {
            if (is_int($key)) {
                $result[$name] = $object->$name;
            } else {
                $result[$key] = $this->getPropertyValueOperation->execute($object, $name);
            }
        }

        return $recursive ? $this->execute($result, $properties) : $result;
    }

    private function processObjectProperties($object, array $properties, bool $recursive): array
    {
        $result = [];
        foreach ($object as $key => $value) {
            $result[$key] = $value;
        }

        return $recursive ? $this->execute($result, $properties) : $result;
    }

    private function valueIsArrayOrObject($value): bool
    {
        return \is_array($value) || \is_object($value);
    }
}
