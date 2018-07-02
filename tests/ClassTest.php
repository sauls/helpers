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
use Sauls\Component\Helper\Stubs\DummyObject;
use Sauls\Component\Helper\Stubs\DummyOneTraitObject;
use Sauls\Component\Helper\Stubs\DummyTwoTraitObject;
use Sauls\Component\Helper\Stubs\Traits\OneVariableTrait;
use Sauls\Component\Helper\Stubs\Traits\UselessTrait;

class ClassTest extends TestCase
{
    /**
     * @test
     */
    public function should_return_class_and_class_child_traits(): void
    {
        $this->assertSame([], class_traits(DummyObject::class));
        $this->assertSame([UselessTrait::class => UselessTrait::class], class_traits(DummyOneTraitObject::class));
        $this->assertSame([
            UselessTrait::class => UselessTrait::class,
            OneVariableTrait::class => OneVariableTrait::class,
        ], class_traits(DummyTwoTraitObject::class));
    }

    /**
     * @test
     */
    public function should_check_if_class_has_given_trait(): void
    {
        $this->assertFalse(class_uses_trait(DummyObject::class, UselessTrait::class));
        $this->assertTrue(class_uses_trait(DummyOneTraitObject::class, UselessTrait::class));
        $this->assertTrue(class_uses_trait(DummyTwoTraitObject::class, UselessTrait::class));
        $this->assertTrue(class_uses_trait(DummyTwoTraitObject::class, OneVariableTrait::class));
    }

    /**
     * @test
     * @dataProvider getClassFqcnData
     */
    public function should_return_class_ufqcn(string $expected, string $class): void
    {
        $this->assertEquals($expected, class_ufqcn($class));
    }

    public function getClassFqcnData(): array
    {
        return[
            ['name', 'My\\Super\\Class\\Name'],
            ['dummy_object', DummyObject::class],
            ['my_class', 'MyClass'],
            ['test', '\Test'],
            ['test', '\test'],
            ['test', 'Vendor\Path\Test'],
            ['test', '\Vendor\Test'],
            ['test_type', '\Vendor\Library\TestType']
        ];
    }
}
