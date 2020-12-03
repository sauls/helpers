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

class Countdown extends AbstractOperation implements CountdownInterface
{
    public function execute($dateFrom = 'now', $dateTo = null, string $format = '%s%02d:%02d:%02d'): string
    {
        $dateFrom = $this->createDateObject($dateFrom);
        $dateTo = $this->createDateObject($dateTo);

        $dateDifference = $dateFrom->diff($dateTo);

        $dateFromHigherThanDateTo = $dateFrom > $dateTo;

        $hours = $dateDifference->h;
        $minutes = $dateDifference->i;
        $seconds = $dateDifference->s;

        $hours += $this->calculateDaysInHours($dateDifference->days);

        return \sprintf($format, $dateFromHigherThanDateTo ? '-' : '', $hours, $minutes, $seconds);
    }

    private function calculateDaysInHours(int $days): int
    {
        if ($days > 0) {
            return $days * self::DAY_IN_HOURS;
        }

        return 0;
    }
}
