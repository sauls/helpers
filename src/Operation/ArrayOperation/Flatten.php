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

class Flatten implements FlattenInterface
{
    /**
     * @var MergeInterface
     */
    private $arrayMergeOperation;

    public function __construct(MergeInterface $arrayMergeOperation)
    {
        $this->arrayMergeOperation = $arrayMergeOperation;
    }

    /**
     * @throws \RuntimeException
     */
    public function execute(array $array): array
    {
        try {
            $result = [];
            foreach (\array_keys($array) as $key) {
                $this->flattenValue($array[$key], $result);
            }

            return \array_values(\array_unique($result));
        } catch (\Throwable $t) {
            throw new \RuntimeException($t->getMessage());
        }
    }

    private function flattenValue($value, array &$result): void
    {
        if (\is_scalar($value)) {
            $result[] = $value;
        }

        if (\is_array($value)) {
            $result = $this->arrayMergeOperation->execute($result, $this->execute($value));
        }

        if (\is_object($value)) {
            $result[] = (string)$value;
        }
    }
}
