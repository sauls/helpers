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

class DiffKey implements DiffKeyInterface
{
    /**
     * @var KeysWithValueInterface
     */
    private $keysWithValueOperation;

    public function __construct(KeysWithValueInterface $keysWithValueOperation)
    {
        $this->keysWithValueOperation = $keysWithValueOperation;
    }

    public function execute(... $arrays): array
    {
        return \array_diff_key(...\array_map([$this, 'convertArrayKeysToKeyValueArray'], $arrays));
    }

    private function convertArrayKeysToKeyValueArray(array $array): array
    {
        return $this->keysWithValueOperation->execute($array);
    }
}
