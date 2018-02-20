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

namespace Sauls\Component\Helper\Operation\DateTimeOperation;

use Sauls\Component\Helper\Operation\Operation;

interface ElapsedTimeInterface extends Operation
{
    public const ELAPSED_TIME_FORMAT_FULL = 'full';
    public const ELAPSED_TIME_FORMAT_SHORT = 'short';

    public function execute($date, array $labels, string $format = self::ELAPSED_TIME_FORMAT_FULL): string;
}
