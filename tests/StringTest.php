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

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Sauls\Component\Helper\Operation\Factory\OperationFactory;
use Sauls\Component\Helper\Operation\StringOperation;

class StringTest extends TestCase
{
    /**
     * @test
     * @dataProvider getDataArrayForStringCamelize
     */
    public function should_camelize_given_strings(string $expected, string $value): void
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
    public function should_snakeify_given_strings(string $expected, string $value): void
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
    public function should_explode_string_by_multiple_delimiters(): void
    {
        $this->assertSame(['one', 'two', 'three'], explode_using_multi_delimiters(['.'], 'one.two.three'));
        $this->assertSame(['one', 'two', 'three'], explode_using_multi_delimiters(['.', ','], 'one.two,three'));
        $this->assertSame(['one', 'two', 'three', 'four'],
            explode_using_multi_delimiters(['.', ',', '#'], 'one.two,three#four'));
        $this->assertSame(['one', 'two', 'three', 'four|five'],
            explode_using_multi_delimiters(['.', ',', '#'], 'one.two,three#four|five'));
    }

    /**
     * @test
     */
    public function should_base64_encode_given_urls(): void
    {
        $this->assertSame('VGhpcyBpcyBhbiBlbmNvZGVkIHN0cmluZw==', base64_url_encode('This is an encoded string'));
        $this->assertSame('YcSNacWrIC0gdGhhbmsgeW91IQ==', base64_url_encode('ačiū - thank you!'));
        $this->assertSame('NTU1LTQ0NC01NTU=', base64_url_encode('555-444-555'));
    }

    /**
     * @test
     */
    public function should_base64_decode_given_urls(): void
    {
        $this->assertSame('This is an encoded string', base64_url_decode('VGhpcyBpcyBhbiBlbmNvZGVkIHN0cmluZw=='));
        $this->assertSame('ačiū - thank you!', base64_url_decode('YcSNacWrIC0gdGhhbmsgeW91IQ=='));
        $this->assertSame('555-444-555', base64_url_decode('NTU1LTQ0NC01NTU='));
    }

    /**
     * @test
     * @dataProvider getStrtrData
     */
    public function should_replace_string_parts_with_given_representations(
        string $expected,
        string $string,
        array $parameters
    ): void {
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
                    's2' => 'world',
                ],
            ],
            [
                'This is test x and this y',
                'This is test %param% and this :param',
                [
                    '%param%' => 'x',
                    ':param' => 'y',
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getCountWordsData
     */
    public function should_count_words_in_given_string(int $expected, string $value): void
    {
        $this->assertEquals($expected, count_words($value));
    }

    public function getCountWordsData(): array
    {
        return [
            [1, 'Word'],
            [2, 'Hello world'],
            [6, 'One two three four five six'],
        ];
    }

    /**
     * @test
     * @dataProvider getCountSentencesData
     */
    public function should_count_sentences_in_given_string(int $expected, string $value): void
    {
        $this->assertEquals($expected, count_sentences($value));
    }

    public function getCountSentencesData(): array
    {
        return [
            [1, 'Hello world.'],
            [2, 'Hello world. And welcome to Html!']
        ];
    }

    /**
     * @test
     * @dataProvider getTruncateData
     */
    public function should_truncate_given_strings(string $expected, string $value, int $length, string $suffix): void
    {
        $this->assertSame($expected, truncate($value, $length, $suffix));
    }

    public function getTruncateData(): array
    {
        return [
            ['Lore...', 'Lore ipsum dollar nipsum', 4, '...'],
            ['Lore ipsum...', 'Lore ipsum dollar nipsum', 10, '...'],
            ['Lore morole šipsūm', 'Lore morole šipsūm dobolar mologar', 18, ''],
            ['Lore', 'Lore', 20, ''],
        ];
    }

    /**
     * @test
     * @dataProvider getTruncateWordsData
     */
    public function should_truncate_given_strings_by_words(string $expected, string $value, int $count, string $suffix): void
    {
        $this->assertSame($expected, truncate_words($value, $count, $suffix));
    }

    public function getTruncateWordsData(): array
    {
        return [
            ['Lore...', 'Lore ipsum dollar molar', 1, '...'],
            ['Lore ipsum...', 'Lore ipsum dollar molar', 2, '...'],
            ['Šarka tupi ant šakos ir valgo', 'Šarka tupi ant šakos ir valgo savo vaikų sriubą', 6, ''],
            ['Hello world!', 'Hello world!', 5, '']
        ];
    }

    /**
     * @test
     * @dataProvider getTruncateSentencesData
     */
    public function should_truncate_given_sentences(string $expected, string $value, int $count, string $suffix): void
    {
        $this->assertSame($expected, truncate_sentences($value, $count, $suffix));
    }

    public function getTruncateSentencesData(): array
    {
        return [
            ['Sentence one.', 'Sentence one. Sentence two. Sentence Three. Sentence four.', 1, ''],
            ['Sentence one. Sentence two.', 'Sentence one. Sentence two. Sentence Three. Sentence four.', 2, ''],
            ['Sentence one. Sentence two. Sentence Three.', 'Sentence one. Sentence two. Sentence Three. Sentence four.', 3, ''],
            ['Sentence one. Sentence two. Sentence Three. Sentence four.', 'Sentence one. Sentence two. Sentence Three. Sentence four.', 4, ''],
            ['Sentence one. Sentence two. Sentence Three. Sentence four.', 'Sentence one. Sentence two. Sentence Three. Sentence four.', 5, ''],
            ['Šiaudas. Ūpėtakis...', 'Šiaudas. Ūpėtakis. Žuvis. Žolė', 2, '..'],
            ['Sentence1. Sentence2.', "Sentence1. \n Sentence2. \n\n\n\n Sentence3", 2, '']
        ];
    }

    /**
     * @test
     * @dataProvider getTruncateHtmlData
     */
    public function should_truncate_html_from_given_string(string $excpected, string $value, int $length, string $suffix): void
    {
        $this->assertSame($excpected, truncate_html($value, $length, $suffix));
    }

    public function getTruncateHtmlData()
    {
        return [
            ['<p>He</p>', '<p>Hello world form HTML.</p>', 2, '']
        ];
    }

    /**
     * @test
     * @dataProvider getTruncateHtmlWordsData
     */
    public function should_truncate_html_words_from_given_string(string $excpected, string $value, int $length, string $suffix): void
    {
        $this->assertSame($excpected, truncate_html_worlds($value, $length, $suffix));
    }

    public function getTruncateHtmlWordsData()
    {
        return [
            ['<p>Hello world</p>', '<p>Hello world form HTML.</p>', 2, '']
        ];
    }

    /**
     * @test
     * @dataProvider getTruncateHtmlSentencesData
     */
    public function should_truncate_html_sentences_from_given_string(string $excpected, string $value, int $length, string $suffix): void
    {
        $this->assertSame($excpected, truncate_html_sentences($value, $length, $suffix));
    }

    public function getTruncateHtmlSentencesData()
    {
        return [
            ['<p>Hello world. Saying from HTML.</p><p>Yes it is working.</p>', '<p>Hello world. Saying from HTML.</p><p>Yes it is working. But as you can see.</p>', 3, ''],
            ['<h1>List of items</h1><ul><li>One</li></ul>', '<h1>List of items</h1><ul><li>One</li><li>Two</li></ul>', 2, ''],
            ['<p><img src="#" /></p><ul><li>A</li></ul>', '<p><img src="#" /></p><ul><li>A</li><li>b</li></ul>', 1, '']
        ];
    }

    /**
     * @test
     */
    public function should_trow_exception_if_wrong_truncate_method_given(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('`notvalid` truncate operation method is not supported.');

        $truncateOperation = OperationFactory::create(StringOperation\TruncateHtml::class);
        $truncateOperation->setTruncateOperationMethod('notvalid');

        $truncateOperation->execute('Simple string', 1, '');
    }

    /**
     * @test
     */
    public function should_string_be_in_given_string(): void
    {
        $stringInOperation = OperationFactory::create(StringOperation\StringIn::class);

        $this->assertTrue($stringInOperation->execute('test', ['test_']));
        $this->assertTrue(string_in('test', ['test_']));
    }

    /**
     * @test
     */
    public function should_not_string_be_in_given_string(): void
    {
        $this->assertFalse(string_in('test_', ['test']));
    }

    /**
     * @test
     */
    public function should_string_contain_given_strings(): void
    {
        $stringContainsOperation = OperationFactory::create(StringOperation\StringContains::class);
        $this->assertTrue($stringContainsOperation->execute('long string to check', ['string', 'int']));
        $this->assertTrue(string_contains('long string to check', ['string', 'int']));
        $this->assertTrue(string_contains('http://localhost/long/route', ['medium', 'long', 'short']));
    }

    /**
     * @test
     */
    public function should_not_contain_given_string(): void
    {
        $this->assertFalse(string_contains('http://localhost/long/route', ['medium', 'short']));
    }
}
