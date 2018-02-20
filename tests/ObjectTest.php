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
     *
     * @throws Exception\PropertyNotAccessibleException
     */
    public function should_define_object_with_given_properties()
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
     * @throws PropertyNotAccessibleException
     */
    public function should_throw_property_not_accessible_exception_when_configuring_object()
    {
        $this->expectException(PropertyNotAccessibleException::class);

        define_object(new DummyObject(), [
           'secret' => 'Not so secret...',
        ]);
    }

    /**
     * @testR
     * @throws PropertyNotAccessibleException
     */
    public function should_get_object_property_value()
    {
        $dummyObject = new DummyObject();
        $dummyObject->property2 = 'This is test';
        $this->assertSame('This is test', get_object_property_value($dummyObject, 'property2'));
        $this->assertSame('', get_object_property_value($dummyObject, 'property1'));
    }

    /**
     * @test
     * @throws PropertyNotAccessibleException
     */
    public function should_throw_property_not_accessible_exception_when_trying_to_get_non_existent_property()
    {
        $this->expectException(PropertyNotAccessibleException::class);
        $dummyObject = new DummyObject();
        get_object_property_value($dummyObject, 'variable1');
    }

    /**
     * @test
     * @throws Exception\ClassPropertyNotSetException
     */
    public function should_set_object_property_value()
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
     * @throws ClassPropertyNotSetException
     */
    public function should_throw_class_property_not_set_exception_when_setting_non_existent_property()
    {
        $this->expectException(ClassPropertyNotSetException::class);

        $dummyObject = new DummyObject();

        set_object_property_value($dummyObject, 'varbiable22', 'Is it real?');
    }
}
