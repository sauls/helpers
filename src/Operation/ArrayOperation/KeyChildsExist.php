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

class KeyChildsExist implements KeyChildsExistInterface
{
    /**
     * @var KeysInterface
     */
    private $keysOperation;

    public function __construct(KeysInterface $keysOperation)
    {
        $this->keysOperation = $keysOperation;
    }

    public function execute($key, array $array): bool
    {
        return false !== strpos(\implode(',', $this->keysOperation->execute($array)), $key.'.');
    }
}
