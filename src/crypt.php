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

use Sauls\Component\Helper\Operation\Factory\OperationFactory;
use Sauls\Component\Helper\Operation\CryptOperation;

/**
 * @throws \Exception
 */
function data_encrypt($data, string $key): string
{
    return OperationFactory::create(CryptOperation\DataEncrypt::class)->execute($data, $key);
}

/**
 * @throws \Exception
 */
function data_decrypt(string $data, string $key)
{
    return OperationFactory::create(CryptOperation\DataDecrypt::class)->execute($data, $key);
}
