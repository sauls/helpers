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

use Carbon\Carbon;
use DateTime;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    /**
     * @test
     * @dataProvider getCountdownDatesData
     */
    public function should_return_correct_countdown_by_given_format($from, $to, $result): void
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
     */
    public function should_print_elapsed_time_short_strings($dateTime, string $expected, array $labels = []): void
    {
        Carbon::withTestNow(
            new DateTime('2010-06-15 15:00:00'),
            fn() => $this->assertStringContainsString(
                $expected,
                elapsed_time($dateTime, $labels, ELAPSED_TIME_FORMAT_SHORT)
            )
        );
    }

    /**
     * @return array
     */
    public function getPrintElapsedTimeShortStringsData(): array
    {
        $now = new DateTime('2010-06-15 15:00:00');

        return [
            [(clone $now)->modify('-3 month -1 day')->format('Y-m-d H:i:s'), '3mo'],
            [(clone $now)->modify('-7 second'), 's'],
            [(clone $now)->modify('-1 second'), 's'],
            [(clone $now)->modify('-7 minute'), 'm'],
            [(clone $now)->modify('-1 hour'), 'h'],
            [(clone $now)->modify('-7 days'), 'w'],
            [(clone $now)->modify('-7 days'), 'savaite', ['singular' => ['{week}' => 'savaite']]],
            [(clone $now)->modify('-1 day'), 'd'],
            [(clone $now)->modify('-7 days'), 'w'],
            [(clone $now)->modify('-1 year'), 'mo'],
        ];
    }

    /**
     * @test
     * @dataProvider getPrintElapsedTimeLongStringsData
     */
    public function should_print_elapsed_time_long_strings($dateTime, string $expected, array $labels = []): void
    {
        Carbon::withTestNow(
            new DateTime('2011-05-04 16:45:09'),
            fn() => $this->assertStringContainsString($expected, elapsed_time($dateTime, $labels))
        );
    }

    /**
     * @return array
     */
    public function getPrintElapsedTimeLongStringsData(): array
    {
        $now = new DateTime('2011-05-04 16:45:09');

        return [
            [(clone $now)->modify('-3 month -1 day')->format('Y-m-d H:i:s'), '3mo'],
            [(clone $now)->modify('-1 year -1 month'), 'yr'],
            [(clone $now)->modify('-1 year -1 month'), 'years', ['singular' => ['{year}' => 'years']]],
            [
                (clone $now)->modify('-1 year -1 month'),
                '18valandu',
                ['singular' => ['{year}' => 'metai', '{month}' => 'menesis'], 'plural' => ['{hours}' => 'valandu']],
            ],
            [
                (clone $now)->modify('-1 year -1 month'),
                '1metai',
                ['singular' => ['{year}' => 'metai', '{month}' => 'menesis'], 'plural' => ['{hours}' => 'valandu']],
            ]
        ];
    }
}
