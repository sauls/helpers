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
use Sauls\Component\Helper\Operation\StringOperation;

/**
 * @throws \Exception
 */
function string_camelize(string $value): string
{
    return OperationFactory::create(StringOperation\Camelize::class)->execute($value);
}

/**
 * @throws \Exception
 */
function string_snakeify(string $value): string
{
    return OperationFactory::create(StringOperation\Snakeify::class)->execute($value);
}

/**
 * @throws \Exception
 */
function explode_using_multi_delimiters(array $delimiters = ['.'], string $value): array
{
    return OperationFactory::create(StringOperation\ExplodeWithMultiDelimiters::class)->execute($delimiters, $value);
}

/**
 * @throws \Exception
 */
function base64_url_encode(string $value): string
{
    return OperationFactory::create(StringOperation\Base64UrlEncode::class)->execute($value);
}

/**
 * @throws \Exception
 */
function base64_url_decode(string $value): string
{
    return OperationFactory::create(StringOperation\Base64Decode::class)->execute($value);
}


