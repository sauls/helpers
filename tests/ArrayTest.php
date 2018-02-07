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

class ArrayTest extends TestCase
{
    /**
     * @test
     * @dataProvider getArraysToMergeData
     */
    public function should_merge_two_given_arrays_into_one(array $expectedArray, array $array1, array $array2)
    {
        $this->assertSame($expectedArray, array_merge($array1, $array2));
    }

    public function getArraysToMergeData(): array
    {
        return [
            [
                [],
                [],
                [],
            ],
            [
                ['test', 'me', 'me', 'test'],
                ['test', 'me'],
                ['me', 'test'],
            ],
            [
                ['test', 'test'],
                [0 => 'test'],
                ['0' => 'test'],
            ],
            [
                ['test', 'test2'],
                [0 => 'test'],
                [0 => 'test2'],
            ],
            [
                ['test1' => ['me', 'other']],
                ['test1' => ['me']],
                ['test1' => ['other']],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getVariousArraysForGetArrayValue()
     *
     * @param mixed $expected
     * @param mixed $array
     * @param mixed $key
     * @param mixed|null $default
     */
    public function should_get_array_value_by_given_values($expected, $array, $key, $default = null)
    {
        $this->assertSame($expected, array_get_value($array, $key, $default));
    }

    /**
     * @throws Exception\PropertyNotAccessibleException
     */
    public function getVariousArraysForGetArrayValue(): array
    {
        return [
            ['test', ['test'], 0],
            [null, 'Hello World!', 11],
            [null, 89, 11],
            ['yep', ['test' => ['me' => ['nested' => 'yep']]], 'test.me.nested'],
            ['yep', ['test' => ['me' => ['nested' => 'yep']]], ['test', 'me', 'nested']],
            [
                'It works!',
                ['a' => 'c', 'g' => configure_object(new \stdClass, ['apache' => 'It works!'])],
                'g.apache',
            ],
            [
                31,
                [
                    'a' => 20,
                    'c' => 11,
                ],
                function ($array, $default) {
                    return $array['a'] + $array['c'];
                },
            ],
            [null, ['what?'], 25],
            ['no value', ['super', 'duper'], 'no', 'no value'],
        ];
    }


    /**
     * @test
     * @dataProvider getArraysForArrayAssign
     *
     * @param mixed $expected
     * @param mixed $array
     * @param mixed $key
     * @param mixed $value
     * @param mixed $getKeyName
     * @param mixed $getKeyValueDefault
     */
    public function should_assign_array_value_by_given_values(
        $expected,
        $array,
        $key,
        $value,
        $getKeyName,
        $getKeyValueDefault = null
    ) {
        array_set_value($array, $key, $value);

        $this->assertSame($expected, array_get_value($array, $getKeyName, $getKeyValueDefault));
    }

    public function getArraysForArrayAssign(): array
    {
        return [
            [null, [], null, [], 'blabla', null],
            [
                'yes!',
                ['a' => 'no', 'b' => 'maybe'],
                null,
                [
                    'g' => 'yes!',
                ],
                'g'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getVariousArraysForSetArrayValue
     *
     * @param mixed $expected
     * @param mixed $array
     * @param mixed $key
     * @param mixed $value
     */
    public function should_set_array_value_by_given_values($expected, $array, $key, $value)
    {
        array_set_value($array, $key, $value);

        $this->assertSame($expected, array_get_value($array, $key));
    }

    public function getVariousArraysForSetArrayValue(): array
    {
        return [
            ['Hello', ['a' => 11, 'b' => 9], 'a', 'Hello'],
            [25, ['a' => 11, 'b' => 9], 'c', 25],
            [
                'Sandbox mode',
                [
                    'a' => 11
                ],
                'b.v.g',
                'Sandbox mode',
            ],
            [
                'World of blocks',
                [
                    'World' => 'Hi!',
                    'deep' => [
                        'not so deep',
                    ]
                ],
                ['deep', 'below', 'block'],
                'World of blocks',
            ],
            [
               ['a', 'b', 'c'],
               [
                   'numbers' => [1, 2, 4],
                   'letters' => ['x', 'v', 'z'],
                   'more' => 'f'
               ],
               'more.letters',
                ['a', 'b', 'c'],
            ]
        ];
    }

    /**
     * @test
     */
    public function should_merge_two_large_arrays()
    {
        $array1 = [
            'a' => 1,
            'b' => [
                'c' => 'hello',
            ],
            'c' => 'g',
            'v' => [
                'u' => [
                    'd' => 'f'
                ],
            ],
            12 => 53,
        ];
        $array2 = [
            'a' => 25,
            'b' => [
                'c' => 'world',
            ],
            11 => 'Yupi!',
            'v' => [
                'u' => [
                    't' => 23
                ]
            ],
            'c' => 111,
            12 => 49,
        ];

        $this->assertSame([
            'a' => 25,
            'b' => [
                'c' => 'world',
            ],
            'c' => 111,
            'v' => [
                'u' => [
                    'd' => 'f',
                    't' => 23,
                ],
            ],
            12 => 53,
            11 => 'Yupi!',
            13 => 49
        ], array_merge($array1, $array2));
    }
}
