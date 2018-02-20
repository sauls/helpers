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

namespace Sauls\Component\Helper\Operation\CryptOperation;

use Defuse\Crypto\Crypto;

class DataEncrypt implements DataEncryptInterface
{
    /**
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function execute($data, string $key): string
    {
        return Crypto::encryptWithPassword(\serialize($data), $key);
    }
}
