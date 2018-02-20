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

class SetValue extends AbstractOperation implements SetValueInterface
{
    public function execute(array &$array, $path, $value): void
    {
        if ($path === null) {
            $array = $value;

            return;
        }

        $keys = $this->splitDotNotatedKeyToArray($path);

        while (\count($keys) > 1) {
            $key = \array_shift($keys);

            $this->assignArrayValue($array, $key);

            $array = &$array[$key];
        }

        $array[\array_shift($keys)] = $value;
    }

    private function assignArrayValue(array &$array, $key): void
    {
        if (!isset($array[$key])) {
            $array[$key] = [];
        }

        if (!\is_array($array[$key])) {
            $array[$key] = [$array[$key]];
        }
    }
}
