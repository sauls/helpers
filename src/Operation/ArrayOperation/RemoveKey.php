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

namespace Sauls\Component\Helper\Operation\ArrayOperation;

class RemoveKey extends AbstractOperation implements RemoveKeyInterface
{
    /**
     * @return mixed
     */
    public function execute(&$array, $key, $default = null)
    {
        $array = &$this->getArrayFromWhichKeyMustBeRemoved($array, $key);

        if ($this->canRemoveKey($array, $key)) {
            return $this->unsetKeyAndReturnItsValue($array, $key);
        }

        return $default;
    }

    private function &getArrayFromWhichKeyMustBeRemoved(&$array, &$key): array
    {
        $keys = $this->splitDotNotatedKeyToArray($key);

        while (\count($keys) > 1) {
            $key = \array_shift($keys);

            if (\is_array($array[$key])) {
                $array = &$array[$key];
            }
        }

        $key = \array_shift($keys);

        return $array;
    }

    private function unsetKeyAndReturnItsValue(&$array, $key)
    {
        $removedKeyValue = $array[$key];
        unset($array[$key]);

        return $removedKeyValue;
    }

    private function canRemoveKey($array, $key): bool
    {
        return \is_array($array) && (isset($array[$key]) || \array_key_exists($key, $array));
    }

}
