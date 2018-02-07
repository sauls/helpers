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

use function define;

define('ELAPSED_TIME_LABEL_SINGLE', 'single');
define('ELAPSED_TIME_LABEL_PLURAL', 'plural');
define('DEFAULT_ELAPSED_TIME_LABELS', [
    ELAPSED_TIME_LABEL_SINGLE => [
        '{year}' => 'yr',
        '{month}' => 'mo',
        '{week}' => 'w',
        '{day}' => 'd',
        '{hour}' => 'h',
        '{minute}' => 'm',
        '{second}' => 's',
    ],
    ELAPSED_TIME_LABEL_PLURAL => [
        '{years}' => 'yr',
        '{months}' => 'mo',
        '{weeks}' => 'w',
        '{days}' => 'd',
        '{hours}' => 'h',
        '{minutes}' => 'm',
        '{seconds}' => 's',
    ],
]);
define('DEFAULT_ELAPSED_TIME_OFFSETS', [
    ['{year}', '{years}', 31557600],
    ['{month}', '{months}', 2592000],
    ['{week}', '{weeks}', 604800],
    ['{day}', '{days}', 86400],
    ['{hour}', '{hours}', 3600],
    ['{minute}', '{minutes}', 60],
    ['{second}', '{seconds}', 1],
]);

/**
 * @param string|\DateTime $date
 */
function print_elapsed_time_short($date, array $elapsedTimeLabels = []): string
{
    return elapsed_time($date, $elapsedTimeLabels)[0];
}

/**
 * @param string|\DateTime $date
 */
function print_elapsed_time_long($date, array $elapsedTimeLabels = []): string
{
    return implode(' ', elapsed_time($date, $elapsedTimeLabels));
}

/**
 * @param string|\DateTime $date
 */
function elapsed_time($date, array $elapsedTimeLabels = []): array
{
    $elapsedTimeLabels = array_merge(DEFAULT_ELAPSED_TIME_LABELS, $elapsedTimeLabels);

    if (\is_string($date)) {
        $date = new \DateTime($date);
    }

    $time = $date->getTimestamp();
    $diff = time() - $time;
    $timeLeft = [];

    foreach (DEFAULT_ELAPSED_TIME_OFFSETS as list($timeSingle, $timePlural, $offset)) {
        if ($diff >= $offset) {
            $left = floor($diff / $offset);
            $diff -= ($left * $offset);
            $timeLeft[] = format_elapsed_time_string((int)$left, $elapsedTimeLabels, [
                ELAPSED_TIME_LABEL_SINGLE => $timeSingle,
                ELAPSED_TIME_LABEL_PLURAL => $timePlural,
            ]);
        }
    }

    return $timeLeft;
}

function format_elapsed_time_string(int $timeLeft, array $elapsedTimeLabels, array $timeStrings): string
{
    return \sprintf(
        '%s%s', $timeLeft,
        (1 === $timeLeft
            ? strtr(
                array_get_value($timeStrings, ELAPSED_TIME_LABEL_SINGLE, ''),
                array_get_value($elapsedTimeLabels, ELAPSED_TIME_LABEL_SINGLE)
            )
            : strtr(
                array_get_value($timeStrings, ELAPSED_TIME_LABEL_PLURAL, ''),
                array_get_value($elapsedTimeLabels, ELAPSED_TIME_LABEL_PLURAL)
            )
        )
    );
}

/**
 * @param string|\DateTime $dateFrom
 * @param string|\DateTime $dateTo
 */
function countdown($dateFrom = 'now', $dateTo, string $outputFormat = '%s%02d:%02d:%02d'): string
{
    $dateFrom = $dateFrom instanceof \DateTime ? $dateFrom : new \DateTime($dateFrom);
    $dateTo = $dateTo instanceof \DateTime ? $dateTo : new \DateTime($dateTo);

    $dateDifference = $dateFrom->diff($dateTo);

    $dateFromHigherThanDateTo = $dateFrom > $dateTo;

    $hours = $dateDifference->h;
    $minutes = $dateDifference->i;
    $seconds = $dateDifference->s;

    if ($dateDifference->days > 0) {
        $hours += $dateDifference->days * 24;
    }

    return \sprintf($outputFormat, $dateFromHigherThanDateTo ? '-' : '', $hours, $minutes, $seconds);
}
