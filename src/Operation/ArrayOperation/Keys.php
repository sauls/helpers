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

class Keys implements KeysInterface
{
    /**
     * @var KeysWithValueInterface
     */
    private $keysWithValueOperation;

    public function __construct(KeysWithValueInterface $keysWithValueOperation)
    {
        $this->keysWithValueOperation = $keysWithValueOperation;
    }

    public function execute(array $array): array
    {
        return \array_keys($this->keysWithValueOperation->execute($array));
    }
}
