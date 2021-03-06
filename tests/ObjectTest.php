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
use Sauls\Component\Helper\Exception\ClassPropertyNotSetException;
use Sauls\Component\Helper\Exception\PropertyNotAccessibleException;
use Sauls\Component\Helper\Stubs\DummyObject;

class ObjectTest extends TestCase
{
    /**
     * @test
     */
    public function should_define_object_with_given_properties(): void
    {
        $dummyObject = define_object(new DummyObject(), [
            'property1' => 'vvv',
            'property2' => 'zzz',
            'property3' => 'xxx',
        ]);

        $this->assertEquals('vvv', $dummyObject->getProperty1());
        $this->assertEquals('zzz', $dummyObject->property2);
        $this->assertEquals('xxx', $dummyObject->property3);
    }

    /**
     * @test
     */
    public function should_throw_property_not_accessible_exception_when_configuring_object(): void
    {
        $this->expectException(PropertyNotAccessibleException::class);

        define_object(new DummyObject(), [
            'secret' => 'Not so secret...',
        ]);
    }

    /**
     * @test
     */
    public function should_get_object_property_value(): void
    {
        $dummyObject = new DummyObject();
        $dummyObject->property2 = 'This is test';
        $this->assertSame('This is test', get_object_property_value($dummyObject, 'property2'));
        $this->assertSame('', get_object_property_value($dummyObject, 'property1'));
    }

    /**
     * @test
     */
    public function should_throw_property_not_accessible_exception_when_trying_to_get_non_existent_property(): void
    {
        $this->expectException(PropertyNotAccessibleException::class);
        $dummyObject = new DummyObject();
        get_object_property_value($dummyObject, 'variable1');
    }

    /**
     * @test
     */
    public function should_set_object_property_value(): void
    {
        $dummyObject = new DummyObject();

        set_object_property_value($dummyObject, 'property1', 'Ignoring programming principles!!');
        set_object_property_value($dummyObject, 'property2', 'p2');
        set_object_property_value($dummyObject, 'property3', 'test');

        $this->assertSame('Ignoring programming principles!!', $dummyObject->getProperty1());
        $this->assertSame('p2', $dummyObject->property2);
        $this->assertSame('test', $dummyObject->property3);
    }

    /**
     * @test
     */
    public function should_throw_class_property_not_set_exception_when_setting_non_existent_property(): void
    {
        $this->expectException(ClassPropertyNotSetException::class);

        $dummyObject = new DummyObject();

        set_object_property_value($dummyObject, 'varbiable22', 'Is it real?');
    }

    /**
     * @test
     * @dataProvider getObjectFqcnData
     */
    public function should_return_object_ucnp(string $expected, object $object): void
    {
        $this->assertEquals($expected, object_ucnp($object));
    }

    public function getObjectFqcnData(): array
    {
        return [
            ['std_class', new \stdClass],
            ['dummy_object', new DummyObject],
        ];
    }

    /**
     * @test
     */
    public function should_return_string_as_string_value_in_array(): void
    {
        $string = 'Hello World!';

        $this->assertSame([$string], object_to_array($string));
    }

    public function should_return_int_as_int_value_in_array(): void
    {
        $int = 56;

        $this->assertSame([$int], object_to_array($int));
    }

    /**
     * @test
     */
    public function should_return_array_when_array_is_passed_as_object(): void
    {
        $array = [
            'KeyA' => 'A',
            'KeuB' => 11,
            23 => 56
        ];

        $this->assertSame($array, object_to_array($array, [], false));
    }

    /**
     * @test
     */
    public function should_return_array_when_array_is_passed_as_object_and_is_recursive(): void
    {
        $array = [
            'KeyA' => 'A',
            'KeuB' => 11,
            'keyC' => [
                'key' => 1,
                'b' => 'value',
            ],
            23 => 56,
        ];

        $this->assertSame($array, object_to_array($array, [], true));
    }

    /**
     * @test
     */
    public function should_return_array_of_given_object_without_properties_and_not_recursive(): void
    {
        $object = define_object(
            new DummyObject,
            [
                'property1' => 24,
                'property2' => 1,
                'property3' => 'Test#X',
                'text' => 'Hello world',
            ]
        );

        $this->assertSame(
            [
                'property2' => 1,
                'property3' => 'Test#X',
                'text' => 'Hello world',
            ],
            object_to_array($object, [], false)
        );
    }

    /**
     * @test
     */
    public function should_return_array_of_given_object_with_properties_and_not_recursive(): void
    {
        $object = define_object(
            new DummyObject,
            [
                'property1' => 24,
                'property2' => 1,
                'property3' => 'Test#X',
                'text' => 'Hello world',
            ]
        );

        $this->assertSame(
            [
                'prop1' => 24,
                'property2' => 1,
                'txt' => 'Hello world',
            ],
            object_to_array($object, [DummyObject::class => ['prop1' => 'property1', 'property2', 'txt' => 'text']], false)
        );
    }

    /**
     * @test
     */
    public function should_return_array_of_given_array_with_properties_and_recursive(): void
    {
        $array = [
            'test' => 1,
            'obj' => define_object(new DummyObject, ['property1' => 'private variable', 'text' => 'Lorem ipsum']),
            'b3' => 'eleven',
        ];

        $this->assertSame([
            'test' => 1,
            'obj' => [
                'p1' => 'private variable',
                'text' => 'Lorem ipsum',
            ],
            'b3' => 'eleven',
        ], object_to_array($array, [
            DummyObject::class => [
                'p1' => 'property1',
                'text'
            ]
        ]));
    }

}
