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

namespace Sauls\Component\Helper;

use Defuse\Crypto\Crypto;

/**
 * @param mixed $encrypt
 *
 * @return string
 * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
 */
function data_encrypt($encrypt, string $key): string
{
    return Crypto::encryptWithPassword(serialize($encrypt), $key);
}

/**
 * @return mixed
 * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
 * @throws \Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException
 */
function data_decrypt(string $decrypt, string $key)
{
    return unserialize(Crypto::decryptWithPassword($decrypt, $key));
}
