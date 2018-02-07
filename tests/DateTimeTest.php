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

use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    /**
     * @test
     * @dataProvider getCountdownDatesData
     *
     * @param $from
     * @param $to
     * @param $result
     */
    public function should_return_correct_countdown_by_given_format($from, $to, $result)
    {
        $this->assertEquals($result, countdown($from, $to));
    }

    /**
     * @return array
     */
    public function getCountdownDatesData(): array
    {
        return [
            ['2016-01-01 00:00:00', '2016-01-01 00:00:01', '00:00:01'],
            ['2016-01-01 00:00:00', '2016-01-02 00:00:01', '24:00:01'],
            ['2016-01-01 00:00:00', '2016-01-03 00:00:01', '48:00:01'],
            ['2016-01-01 00:00:00', '2016-01-04 00:00:01', '72:00:01'],
            ['2016-01-01 00:00:00', '2016-01-05 00:00:01', '96:00:01'],
            ['2016-01-01 00:00:00', '2016-01-03 22:35:00', '70:35:00'],
            ['2016-01-02 00:01:00', '2016-01-01 01:23:59', '-22:37:01'],
        ];
    }

    /**
     * @test
     * @dataProvider getPrintElapsedTimeShortStringsData
     *
     * @param string|\DateTime $dateTime
     */
    public function should_print_elapsed_time_short_strings($dateTime, string $expected, array $labels = [])
    {
        $this->assertContains($expected, print_elapsed_time_short($dateTime, $labels));
    }

    /**
     * @return array
     */
    public function getPrintElapsedTimeShortStringsData(): array
    {
        $now = new \DateTime();

        return [
            [(clone $now)->modify('-3 month -1 hour -29 minutes -26 seconds')->format('Y-m-d H:i:s'), '3mo'],
            [(new \DateTime())->modify('-7 second'), 's'],
            [(new \DateTime())->modify('-1 second'), 's'],
            [(new \DateTime())->modify('-7 minute'), 'm'],
            [(new \DateTime())->modify('-1 hour'), 'h'],
            [(new \DateTime())->modify('-7 days'), 'w'],
            [(new \DateTime())->modify('-7 days'), 'savaite', ['single' => ['{week}' => 'savaite']]],
            [(new \DateTime())->modify('-1 day'), 'd'],
            [(new \DateTime())->modify('-1 month'), 'mo'],
            [(new \DateTime())->modify('-1 year'), 'mo'],
        ];
    }

    /**
     * @test
     * @dataProvider getPrintElapsedTimeLongStringsData
     *
     * @param string|\DateTime $dateTime
     */
    public function should_print_elapsed_time_long_strings($dateTime, string $expected, array $labels = [])
    {
        $this->assertContains($expected, print_elapsed_time_long($dateTime, $labels));
    }

    /**
     * @return array
     */
    public function getPrintElapsedTimeLongStringsData(): array
    {
        $now = new \DateTime();

        return [
            [(clone $now)->modify('-3 month -1 hour -29 minutes -26 seconds')->format('Y-m-d H:i:s'), '3mo 2d 1h 29m'],
            [(new \DateTime())->modify('-1 year -1 month'), 'yr'],
            [(new \DateTime())->modify('-1 year -1 month'), 'years', ['single' => ['{year}' => 'years']]],
            [
                (new \DateTime())->modify('-1 year -1 month'),
                '1metai 1menesis 18valandu',
                ['single' => ['{year}' => 'metai', '{month}' => 'menesis'], 'plural' => ['{hours}' => 'valandu']],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getFormatElapsedTimeStringData
     */
    public function should_format_elapsed_time_strings(
        $expected,
        int $timeLeft,
        array $elapsedTimeLabels,
        array $timeStrings
    ) {
        $this->assertSame($expected, format_elapsed_time_string($timeLeft, $elapsedTimeLabels, $timeStrings));
    }

    public function getFormatElapsedTimeStringData(): array
    {
        return [
            ['1s', 1, DEFAULT_ELAPSED_TIME_LABELS, ['single' => '{second}']],
            ['2s', 2, DEFAULT_ELAPSED_TIME_LABELS, ['plural' => '{seconds}']],
            [
                '1-eri metai',
                1,
                array_merge(DEFAULT_ELAPSED_TIME_LABELS, ['single' => ['{year}' => '-eri metai']]),
                ['single' => '{year}'],
            ],
            [
                '23menesiai',
                23,
                array_merge(DEFAULT_ELAPSED_TIME_LABELS, ['plural' => ['{months}' => 'menesiai']]),
                ['plural' => '{months}'],
            ]
        ];
    }
}
