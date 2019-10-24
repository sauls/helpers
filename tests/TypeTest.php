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
use Sauls\Component\Helper\Exception\ConverterNotFoundException;
use Sauls\Component\Helper\Exception\InvalidTypeConverterException;
use Sauls\Component\Helper\Stubs\DummyObject;
use Sauls\Component\Helper\Stubs\Converter\CustomTypeAConverter;
use Sauls\Component\Helper\Stubs\Converter\CustomTypeBConverter;
use Sauls\Component\Helper\Stubs\JsonSerializableObject;
use Sauls\Component\Helper\Stubs\StringObject;
use Sauls\Component\Helper\Stubs\TraversableObject;

class TypeTest extends TestCase
{
    /**
     * @test
     */
    public function should_convert_array_type(): void
    {
        $this->assertIsArray(convert_to([], 'array'));
        $this->assertIsArray(convert_to([], '[]'));
        $this->assertIsArray(convert_to([1, 2, 4], 'array'));
        $this->assertIsArray(convert_to('string value', 'array'));
        $this->assertIsArray(convert_to(123, 'array'));
        $this->assertSame(
            [
            'a' => 1,
            'b' => 2,
            ],~
            convert_to(new TraversableObject, 'array')
        );
        $this->assertSame(
            [
               'x' => 2,
               'y' => 14,
            ],
            convert_to(new JsonSerializableObject, 'array')
        );
    }

    /**
     * @test
     */
    public function should_throw_exception_when_type_converter_is_not_registered(): void
    {
        $this->expectException(InvalidTypeConverterException::class);
        $this->expectExceptionMessage('Invalid converter type `unknown`.');
        convert_to('23', 'unknown');
    }

    /**
     * @test
     * @throws \Exception
     */
    public function should_convert_to_custom_a_type()
    {
        register_converters([
            new CustomTypeAConverter,
            new CustomTypeBConverter,
        ]);

        $result = convert_to('Hello', 'custom');

        $this->assertSame('Hello', $result);
    }

    /**
     * @test
     */
    public function should_convert_to_custom_b_type(): void
    {
        register_converters([
            new CustomTypeAConverter,
            new CustomTypeBConverter,
        ]);

        $result = convert_to(new StringObject, 'custom');

        $this->assertSame('custom something!', $result);
    }

    /**
     * @test
     */
    public function should_throw_convertr_not_found_exception(): void
    {
        $this->expectException(ConverterNotFoundException::class);
        $this->expectExceptionMessage('Given value of `object` does not have any converters to type `custom`.');

        register_converters(
            [
                new CustomTypeAConverter,
                new CustomTypeBConverter,
            ]
        );

        convert_to(new DummyObject(), 'custom');
    }
}
