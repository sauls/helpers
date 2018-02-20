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

class KeyExists extends AbstractOperation implements KeyExistsInterface
{
    public function execute($key, array $array): bool
    {
        $keys = $this->splitDotNotatedKeyToArray($key);

        while (\count($keys) > 1) {
            $key = \array_shift($keys);

            if ($this->keyIsSetAndIsArray($array, $key)) {
                $array = &$array[$key];
            }
        }

        $key = \array_shift($keys);

        return $this->keyIsSetAndExists($array, $key);
    }

    private function keyIsSetAndIsArray(array $array, $key): bool
    {
        return isset($array[$key]) && \is_array($array[$key]);
    }

    private function keyIsSetAndExists(array $array, $key): bool
    {
        return isset($array[$key]) || \array_key_exists($key, $array);
    }
}
