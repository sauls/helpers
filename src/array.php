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

use Sauls\Component\Helper\Exception\PropertyNotAccessibleException;
use Sauls\Component\Helper\Operation\ArrayOperation;
use Sauls\Component\Helper\Operation\Factory\OperationFactory;

function array_merge(array ...$arrays): array
{
    return \call_user_func_array(
        [
            OperationFactory::create(ArrayOperation\Merge::class),
            'execute',
        ],
        $arrays
    );
}

/**
 * @throws PropertyNotAccessibleException
 * @throws \Exception
 */
function array_get_value($array, $key, $default = null)
{
    return OperationFactory::create(ArrayOperation\GetValue::class)->execute($array, $key, $default);
}

/**
 * @throws \Exception
 */
function array_set_value(array &$array, $key, $value): void
{
    OperationFactory::create(ArrayOperation\SetValue::class)->execute($array, $key, $value);
}

/**
 * @throws \Exception
 */
function array_remove_key(&$array, $key, $default = null)
{
    return OperationFactory::create(ArrayOperation\RemoveKey::class)->execute($array, $key, $default);
}

/**
 * @throws \Exception
 */
function array_key_exists($key, array $array): bool
{
    return OperationFactory::create(ArrayOperation\KeyExists::class)->execute($key, $array);
}

/**
 * @throws \Exception
 */
function array_remove_value(&$array, $value): array
{
    return OperationFactory::create(ArrayOperation\RemoveValue::class)->execute($array, $value);
}

/**
 * @throws \Exception
 */
function array_deep_search(array $array, $searchValue)
{
    return OperationFactory::create(ArrayOperation\DeepSearch::class)->execute($array, $searchValue);
}

/**
 * @throws \RuntimeException
 * @throws \Exception
 */
function array_flatten(array $array): array
{
    return OperationFactory::create(ArrayOperation\Flatten::class)->execute($array);
}

/**
 * @throws \Exception
 */
function array_multiple_keys_exists(array $keys, array $array): bool
{
    return OperationFactory::create(ArrayOperation\MultipleKeysExists::class)->execute($keys, $array);
}

/**
 * @throws \Exception
 */
function array_keys(array $array): array
{
    return OperationFactory::create(ArrayOperation\Keys::class)->execute($array);
}

/**
 * @throws \Exception
 */
function array_keys_with_value(array $array): array
{
    return OperationFactory::create(ArrayOperation\KeysWithValue::class)->execute($array);
}

/**
 * @throws \Exception
 */
function array_diff_key(... $arrays): array
{
    return \call_user_func_array(
        [
            OperationFactory::create(ArrayOperation\DiffKey::class),
            'execute',
        ],
        $arrays
    );
}

function array_key_childs_exist($key, array $array): bool
{
    return OperationFactory::create(ArrayOperation\KeyChildsExist::class)->execute($key, $array);
}
