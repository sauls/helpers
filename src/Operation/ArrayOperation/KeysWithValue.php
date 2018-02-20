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

class KeysWithValue implements KeysWithValueInterface
{
    /**
     * @var MergeInterface
     */
    private $mergeOperation;

    public function __construct(MergeInterface $mergeOperation)
    {
        $this->mergeOperation = $mergeOperation;
    }

    public function execute(array $array): array
    {
        return $this->createKeys($array);
    }

    private function createKeys(array $array, string $prefix = ''): array
    {
        $keysWithValue = [];

        foreach ($array as $key => $value) {
            if (\is_array($value) && !empty($value)) {
                $keysWithValue = $this->mergeOperation->execute($keysWithValue, $this->createKeys($value, $prefix.$key.'.'));
            } else {
                $keysWithValue[$prefix.$key] = $value;
            }
        }

        return $keysWithValue;
    }
}
