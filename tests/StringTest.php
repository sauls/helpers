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

class StringTest extends TestCase
{
    /**
     * @test
     * @dataProvider getDataArrayForStringCamelize
     */
    public function should_camelize_given_strings(string $expected, string $value)
    {
        $this->assertSame($expected, string_camelize($value));
    }

    public function getDataArrayForStringCamelize(): array
    {
        return [
            ['Text', 'text'],
            ['SimpleWord', 'simple word'],
            ['SimpleWord', 'simple_word'],
            ['Simple_Word', 'simple.word'],
            ['Simple_Class', 'simple\\class'],
        ];
    }

    /**
     * @test
     * @dataProvider getDataArrayForStringSnakeify
     */
    public function should_snakeify_given_strings(string $expected, string $value)
    {
        $this->assertSame($expected, string_snakeify($value));
    }

    public function getDataArrayForStringSnakeify(): array
    {
        return [
            ['test_test', 'TestTest'],
            ['this_is_snake_string', 'thisIsSnakeString'],
            ['test25_link', 'test25Link'],
        ];
    }

    /**
     * @test
     */
    public function should_explode_string_by_multiple_delimiters()
    {
        $this->assertSame(['one', 'two', 'three'], explode_using_multi_delimiters(['.'], 'one.two.three'));
        $this->assertSame(['one', 'two', 'three'], explode_using_multi_delimiters(['.', ','], 'one.two,three'));
        $this->assertSame(['one', 'two', 'three', 'four'], explode_using_multi_delimiters(['.', ',', '#'], 'one.two,three#four'));
        $this->assertSame(['one', 'two', 'three', 'four|five'], explode_using_multi_delimiters(['.', ',', '#'], 'one.two,three#four|five'));
    }

    /**
     * @test
     */
    public function should_base64_encode_given_urls()
    {
        $this->assertSame('VGhpcyBpcyBhbiBlbmNvZGVkIHN0cmluZw==', base64_url_encode('This is an encoded string'));
        $this->assertSame('YcSNacWrIC0gdGhhbmsgeW91IQ==', base64_url_encode('ačiū - thank you!'));
        $this->assertSame('NTU1LTQ0NC01NTU=', base64_url_encode('555-444-555'));
    }

    /**
     * @test
     */
    public function should_base64_decode_given_urls()
    {
        $this->assertSame('This is an encoded string', base64_url_decode('VGhpcyBpcyBhbiBlbmNvZGVkIHN0cmluZw=='));
        $this->assertSame('ačiū - thank you!', base64_url_decode('YcSNacWrIC0gdGhhbmsgeW91IQ=='));
        $this->assertSame('555-444-555', base64_url_decode('NTU1LTQ0NC01NTU='));
    }

    /**
     * @test
     * @dataProvider getStrtrData
     */
    public function should_replace_string_parts_with_given_representations(string $expected, string $string, array $parameters)
    {
        $this->assertSame($expected, strtr($string, $parameters));
    }

    public function getStrtrData()
    {
        return [
            [
                'Hello world',
                '{s1} s2',
                [
                    '{s1}' => 'Hello',
                    's2' => 'world'
                ]
            ],
            [
                'This is test x and this y',
                'This is test %param% and this :param',
                [
                    '%param%' => 'x',
                    ':param' => 'y'
                ]
            ]
        ];
    }
}
