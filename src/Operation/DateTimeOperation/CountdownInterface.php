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

interface CountdownInterface extends Operation
{
    public const DAY_IN_HOURS = 24;

    public function execute($dateFrom = 'now', $dateTo, string $format = '%s%02d:%02d:%02d'): string;
}
