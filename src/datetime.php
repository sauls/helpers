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
use Sauls\Component\Helper\Operation\DateTimeOperation;

\define('ELAPSED_TIME_FORMAT_FULL', DateTimeOperation\ElapsedTime::ELAPSED_TIME_FORMAT_FULL);
\define('ELAPSED_TIME_FORMAT_SHORT', DateTimeOperation\ElapsedTime::ELAPSED_TIME_FORMAT_SHORT);

/**
 * @throws \Exception
 */
function elapsed_time($date, array $labels = [], $format = ELAPSED_TIME_FORMAT_FULL): string
{
    return OperationFactory::create(DateTimeOperation\ElapsedTime::class)
        ->execute($date, $labels, $format);
}

/**
 * @throws \Exception
 */
function countdown($dateFrom = 'now', $dateTo = null, string $format = '%s%02d:%02d:%02d'): string
{
    return OperationFactory::create(DateTimeOperation\Countdown::class)->execute($dateFrom, $dateTo, $format);
}
