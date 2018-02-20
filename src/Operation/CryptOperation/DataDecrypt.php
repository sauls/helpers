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

class DataDecrypt implements DataDecryptInterface
{
    public function execute(string $data, string $key)
    {
        return \unserialize(Crypto::decryptWithPassword($data, $key));
    }
}
