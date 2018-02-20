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

class Merge implements MergeInterface
{
    public function execute(... $arrays): array
    {
        $result = \array_shift($arrays);
        while (!empty($arrays)) {
            $nextArray = \array_shift($arrays);
            $this->mergeNextArray($nextArray, $result);
        }

        return $result;
    }

    private function mergeNextArray(array $nextArray, &$result): void
    {
        foreach ($nextArray as $key => $value) {
            $this->mergeCurrentArrayValues($key, $value, $result);
        }
    }

    private function mergeCurrentArrayValues($key, $value, &$result): void
    {
        if (\is_int($key)) {
            $this->mergeIntegerKeyValue($key, $value, $result);
        } elseif ($this->canMergeArrayValue($key, $value, $result)) {
            $result[$key] = $this->execute($result[$key], $value);
        } else {
            $result[$key] = $value;
        }
    }

    private function canMergeArrayValue($key, $value, $result): bool
    {
        return \is_array($value) && isset($result[$key]) && \is_array($result[$key]);
    }

    private function mergeIntegerKeyValue($key, $value, &$result): void
    {
        if (\array_key_exists($key, $result)) {
            $result[] = $value;
        } else {
            $result[$key] = $value;
        }
    }
}
