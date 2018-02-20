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

class GetPropertyValue extends AbstractOperation implements GetPropertyValueInterface
{
    /**
     * @return mixed
     * @throws PropertyNotAccessibleException
     */
    public function execute(object $object, string $property, array $methodPrefixes = ['get', 'is'])
    {
        try {
            foreach ($methodPrefixes as $getterMethodPrefix) {
                $getterMethod = $this->createPropertyMethodName($getterMethodPrefix, $property);

                if (\method_exists($object, $getterMethod)) {
                    return $object->$getterMethod();
                }
            }

            return $object->$property;
        } catch (\Throwable $t) {
            throw new PropertyNotAccessibleException($t->getMessage());
        }
    }
}
