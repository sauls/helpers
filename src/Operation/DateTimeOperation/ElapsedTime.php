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

use Sauls\Component\Helper\Operation\ArrayOperation;

class ElapsedTime extends AbstractOperation implements ElapsedTimeInterface
{
    private $labels = [
        'singular' => [
            '{year}' => 'yr',
            '{month}' => 'mo',
            '{week}' => 'w',
            '{day}' => 'd',
            '{hour}' => 'h',
            '{minute}' => 'm',
            '{second}' => 's',
        ],
        'plural' => [
            '{years}' => 'yr',
            '{months}' => 'mo',
            '{weeks}' => 'w',
            '{days}' => 'd',
            '{hours}' => 'h',
            '{minutes}' => 'm',
            '{seconds}' => 's',
        ],
    ];

    private $timeOffsets = [
        ['{year}', '{years}', 31557600],
        ['{month}', '{months}', 2592000],
        ['{week}', '{weeks}', 604800],
        ['{day}', '{days}', 86400],
        ['{hour}', '{hours}', 3600],
        ['{minute}', '{minutes}', 60],
        ['{second}', '{seconds}', 1],
    ];


    /**
     * @var ArrayOperation\MergeInterface
     */
    private $arrayMergeOperation;

    /**
     * @var ArrayOperation\GetValue
     */
    private $arrayGetValueOperation;

    public function __construct(
        ArrayOperation\MergeInterface $arrayMergeOperation,
        ArrayOperation\GetValue $arrayGetValueOperation
    ) {
        $this->arrayMergeOperation = $arrayMergeOperation;
        $this->arrayGetValueOperation = $arrayGetValueOperation;
    }

    /**
     * @throws \Sauls\Component\Helper\Exception\PropertyNotAccessibleException
     */
    public function execute($date, array $labels, string $format = self::ELAPSED_TIME_FORMAT_FULL): string
    {
        $elapsedTimeLabels = $this->arrayMergeOperation->execute($this->labels, $labels);

        $date = $this->createDateObject($date);

        $time = $date->getTimestamp();
        $timeDifference = time() - $time;
        $timeLeftValues = [];

        foreach ($this->timeOffsets as [$timeSingle, $timePlural, $offset]) {
            if ($timeDifference >= $offset) {

                $timeLeft = floor($timeDifference / $offset);
                $timeDifference -= ($timeLeft * $offset);
                $timeLeftValues[] = $this->formatLabel((int)$timeLeft, $elapsedTimeLabels, [
                    'singular' => $timeSingle,
                    'plural' => $timePlural,
                ]);
            }
        }

        return $this->outputElapsedTime($timeLeftValues, $format);
    }

    /**
     * @throws \Sauls\Component\Helper\Exception\PropertyNotAccessibleException
     */
    private function formatLabel(int $timeLeft, array $labels, array $timeStrings): string
    {
        return \sprintf(
            '%s%s', $timeLeft,
            (1 === $timeLeft
                ? $this->formatSingularLabel($labels, $timeStrings)
                : $this->formatPlurarLabel($labels, $timeStrings)
            )
        );
    }

    /**
     * @throws \Sauls\Component\Helper\Exception\PropertyNotAccessibleException
     */
    private function formatSingularLabel(array $labels, array $timeStrings): string
    {
        return \strtr(
            $this->arrayGetValueOperation->execute($timeStrings, 'singular', ''),
            $this->arrayGetValueOperation->execute($labels, 'singular', '')
        );
    }

    /**
     * @throws \Sauls\Component\Helper\Exception\PropertyNotAccessibleException
     */
    private function formatPlurarLabel(array $labels, array $timeStrings): string
    {
        return \strtr(
            $this->arrayGetValueOperation->execute($timeStrings, 'plural', ''),
            $this->arrayGetValueOperation->execute($labels, 'plural', '')
        );
    }

    /**
     * @throws \Sauls\Component\Helper\Exception\PropertyNotAccessibleException
     */
    private function outputElapsedTime(array $timeLeftValues, string $format): string
    {
        if (self::ELAPSED_TIME_FORMAT_SHORT === $format) {
            return $this->arrayGetValueOperation->execute($timeLeftValues, 0, '');
        }

        return \implode(' ', $timeLeftValues);
    }
}
