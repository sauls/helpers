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


use Sauls\Component\Helper\Exception\PropertyNotAccessibleException;

class DefineObject extends AbstractOperation implements DefineObjectInterface
{

    /**
     * @param object $object
     * @param array  $properties
     * @param array  $methodPrefixes
     *
     * @return object
     * @throws PropertyNotAccessibleException
     */
    public function execute(object $object, array $properties, array $methodPrefixes = ['set', 'add']): object
    {
        try {
            foreach ($properties as $property => $value) {
                if (false === $this->assignValueWithSetterMethod($object, [$property, $value], $methodPrefixes)) {
                    $object->$property = $value;
                }
            }

            return $object;
        } catch (\Throwable $t) {
            throw new PropertyNotAccessibleException($t->getMessage());
        }
    }

    private function assignValueWithSetterMethod(object $object, array $parameters, array $methodPrefixes): bool
    {
        [$property, $value] = $parameters;
        foreach ($methodPrefixes as $setterMethodPrefix) {

            $setterMethod = $this->createPropertyMethodName($setterMethodPrefix, $property);

            if (\method_exists($object, $setterMethod)) {
                $object->$setterMethod($value);
                return true;
            }
        }

        return false;
    }
}
