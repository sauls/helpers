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
use Sauls\Component\Helper\Operation\TypeOperation;

/**
 * @throws \Exception
 */
function convert_to($value, string $type, array $ensurers = [])
{
    return OperationFactory::create(TypeOperation\ConvertTo::class)->execute($value, $type, $ensurers);
}

function register_converters(array $converters): void
{
    TypeOperation\ConvertTo::addConverters($converters);
}
