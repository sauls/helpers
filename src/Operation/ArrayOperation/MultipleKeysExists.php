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

class MultipleKeysExists implements MultipleKeysExistsInterface
{
    /**
     * @var KeyExistsInterface
     */
    private $keyExistsOperation;

    public function __construct(KeyExistsInterface $keyExistsOperation)
    {
        $this->keyExistsOperation = $keyExistsOperation;
    }

    public function execute(array $keys, array $array): bool
    {
        if (empty($keys)) {
            return false;
        }

        $result = true;

        foreach ($keys as $key) {
            $result &= $this->keyExistsOperation->execute($key, $array);
        }

        return $result ? true : false;
    }
}
