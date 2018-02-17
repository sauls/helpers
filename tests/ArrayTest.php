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
use Sauls\Component\Helper\Stubs\StringObject;

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
     * @param mixed      $expected
     * @param mixed      $array
     * @param mixed      $key
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
                'yep',
                [
                    'g' => 11,
                    'c' => [
                        'a' => 1,
                    ],
                    'test.key' => 'yep',
                    'h' => 11,
                ],
                'test.key'
            ],
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
                'g',
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
                    'a' => 11,
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
                    ],
                ],
                ['deep', 'below', 'block'],
                'World of blocks',
            ],
            [
                ['a', 'b', 'c'],
                [
                    'numbers' => [1, 2, 4],
                    'letters' => ['x', 'v', 'z'],
                    'more' => 'f',
                ],
                'more.letters',
                ['a', 'b', 'c'],
            ],
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
                    'd' => 'f',
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
                    't' => 23,
                ],
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
            13 => 49,
        ], array_merge($array1, $array2));
    }

    /**
     * @test
     * @dataProvider getArrayRemoveKeyData
     *
     * @param mixed      $expected
     * @param mixed      $key
     * @param null|mixed $default
     */
    public function should_remove_array_element_by_given_key(
        $expected,
        array $array,
        $key,
        $default = null,
        array $expectedArray = []
    ) {
        $this->assertSame($expected, array_remove_key($array, $key, $default));

        if (!empty($expectedArray)) {
            $this->assertEquals($expectedArray, $array);
        }
    }

    public function getArrayRemoveKeyData(): array
    {
        return [
            [null, [], 'test'],
            [5, [], 'age', 5],
            [83, [11, 22, 83], 2],
            [22, [11, 22, 83], 1, null, [0 => 11, 2 => 83]],
            [
                'yiw!',
                [
                    'a' => [
                        'b' => 'yiw!',
                    ],
                ],
                'a.b',
                null,
                [
                    'a' => [],
                ],
            ],
            [
                'is it working?',
                [
                    'a' => 'Hello',
                    'b' => [1, 2],
                    'c' => [
                        'g' => [
                            'y' => 'function',
                            'v' => 'is it working?',
                            'x' => 'factor',
                        ],
                    ],
                    'd' => [],
                ],
                'c.g.v',
                null,
                [
                    'a' => 'Hello',
                    'b' => [1, 2],
                    'c' => [
                        'g' => [
                            'y' => 'function',
                            'x' => 'factor',
                        ],
                    ],
                    'd' => [],
                ],
            ],
            [
                'x',
                [
                    'a' => [
                        'b' => [
                            'c' => 'x',
                        ],
                    ],
                ],
                ['a', 'b', 'c'],
                null,
                [
                    'a' => [
                        'b' => [],
                    ],
                ],
            ],
            [
                null,
                [
                    'c' => [
                        'x' => [
                            'v' => 'ttt',
                            'b' => 11,
                        ],
                    ],
                ],
                'c.x.a',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getArrayRemoveValueData
     *
     * @param mixed $expected
     * @param mixed $value
     */
    public function should_remove_array_element_by_given_value(
        $expected,
        array $array,
        $value,
        array $expectedArray = []
    ) {
        $this->assertSame($expected, array_remove_value($array, $value));

        if (!empty($expectedArray)) {
            $this->assertEquals($expectedArray, $array);
        }
    }

    public function getArrayRemoveValueData(): array
    {
        return [
            [[], [], 'value', []],
            [
                ['x' => 11],
                [
                    'y' => 25,
                    'x' => 11,
                ],
                11,
                [
                    'y' => 25,
                ],
            ],
            [
                [
                    'marry' => 'carson',
                    'john' => 'carson',
                ],
                [
                    'johan' => 'doe',
                    'marry' => 'carson',
                    'tim' => 'west',
                    'andy' => 'gear',
                    'john' => 'carson',
                ],
                'carson',
                [
                    'johan' => 'doe',
                    'tim' => 'west',
                    'andy' => 'gear',
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getArrayKeyExistsData()
     *
     * @param mixed $expected
     * @param mixed $key
     */
    public function should_check_if_given_key_exists_in_array($expected, $array, $key, bool $caseSensitive = true)
    {
        $this->assertSame($expected, array_key_exists($array, $key, $caseSensitive));
    }

    public function getArrayKeyExistsData(): array
    {
        return [
            [false, [], 'x'],
            [
                false, [], 'a.b.c.d'
            ],
            [
                true,
                [
                    'b' => 11,
                    'a' => 23,
                ],
                ['a']
            ],
            [
                true,
                [
                    'b' => 11,
                    'a' => 23,
                    'd' => [
                        'g' => [
                            'f' => true
                        ]
                    ]
                ],
                ['d', 'g', 'f']
            ],
            [
                true,
                [
                    'x' => 11,
                    'y' => 12,
                ],
                'y'
            ],
            [
                false,
                [
                    'x' => 11,
                    'y' => 12,
                ],
                'Y',
            ],
            [
                true,
                [
                    'x' => 11,
                    'Y' => 12,
                ],
                'Y',
                false
            ],
            [
                true,
                [
                    'a' => [
                        'b' => 'value'
                    ],
                    'c' => 44,
                ],
                'a.b'
            ],
            [
                false,
                [
                    'a' => [
                        'b' => 'value'
                    ],
                    'c' => 44,
                ],
                'a.B'
            ],
            [
                true,
                [
                    'a' => [
                        'B' => 'value'
                    ],
                    'c' => 44,
                ],
                'a.B',
                false
            ],
        ];
    }

    /**
     * @test
     * @expectedException \TypeError
     */
    public function should_throw_error_when_not_array_passed_to_array_key_exists()
    {
        array_key_exists('I am a string.', 'value');
    }

    /**
     * @test
     * @dataProvider getArrayDeepSearchData
     *
     */
    public function should_return_array_deep_search_array_with_path_arrays_to_value($expected, array $array, $value)
    {
        $this->assertSame($expected, array_deep_search($array, $value));
    }

    /**
     * @return array
     */
    public function getArrayDeepSearchData(): array
    {
        return [
            [[], [], ''],
            [
                [],
                [
                    'a' => 'b',
                    'c' => 'd',
                    'g' => [
                        'v' => 'm',
                    ]
                ],
                'o'
            ],
            [
                [
                    0 => [
                        0 => 'c'
                    ],
                ],
                [
                    'a' => 'b',
                    'c' => 'd',
                    'g' => [
                        'v' => 'm',
                    ]
                ],
                'd'
            ],
            [
                [
                    0 => [
                        0 => 'g',
                        1 => 'v',
                    ]
                ],
                [
                    'a' => 'b',
                    'c' => 'd',
                    'g' => [
                        'v' => 'm',
                    ]
                ],
                'm'
            ],
            [
                [
                    0 => [
                        0 => 'j',
                        1 => 'f',
                        2 => 'cd',
                    ],
                    1 => [
                        0 => 'g',
                        1 => 'v',
                    ]
                ],
                [
                    'a' => 'b',
                    'j' => [
                        'f' => [
                            'cd' => 'm',
                        ],
                    ],
                    'c' => 'd',
                    'g' => [
                        'v' => 'm',
                    ]
                ],
                'm'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getArrayDeppSearchValueData
     *
     * @param mixed $key
     * @param mixed $value
     * @param mixed $searchValue
     */
    public function should_return_array_deep_search_value_path_array(array $expected, $key, $value, $searchValue)
    {
        $this->assertSame($expected, array_deep_search_value($key, $value, $searchValue));
    }

    public function getArrayDeppSearchValueData(): array
    {
        return [
            [
                [], 'test', [], 'test',
            ],
            [
                [0 => 'test'],
                'test',
                [
                    'x' => 1,
                    'y' => 2,
                    'test' => 11,
                ],
                11,
            ],
            [
                [
                    0 => 'test',
                    1 => 'p',
                    2 => 'a',
                ],
                'test',
                [
                    'y' => 'u',
                    'test' => [
                        'o' => 11,
                        'p' => [
                            'a' => 23
                        ],
                    ],
                ],
                23
            ]
        ];
    }

    /**
     * @test
     * @dataProvider getArrayFlattenData
     */
    public function should_flatten_given_array($expected, $array)
    {
        $this->assertSame($expected, array_flatten($array));
    }

    public function getArrayFlattenData(): array
    {
        return [
            [
                [], [],
            ],
            [
                [
                    0 => 11,
                    1 => 23,
                ],
                [
                    'a' => 11,
                    'b' => 23,
                ],
            ],
            [
                [
                    0 => 11,
                    1 => 28,
                    2 => 13,
                ],
                [
                    'a' => 11,
                    'b' => [
                        'c' => 28,
                        'a' => 13,
                    ],
                ],
            ],
            [
                [
                    0 => 15,
                    1 => 'hello',
                    2 => 'world',
                    3 => 'life',
                    4 => 'string object',
                    5 => 25,
                    6 => 98,
                ],
                [
                    'a' => 15,
                    'b' => [
                        'c' => 'hello',
                        'g' => [
                            'world', 'life'
                        ],
                        'h' => new StringObject(),
                    ],
                    'v' => 'life',
                    'm' => [
                        15, 25, 98
                    ],
                ],
            ]
        ];
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function should_throw_exception_when_object_cannot_be_converted_to_string()
    {
        array_flatten([
           'a' => 11,
           'b' => new DummyObject(),
        ]);
    }

    /**
     * @test
     */
    public function should_check_if_multiple_array_keys_exists()
    {
        $array = [
            'k1' => 11,
            'k2' => 11,
            'k3' => 11,
            'x' => [
                'break' => [
                    'deeper' => 'secret project',
                ],
            ],
            'k4' => 11,
            'k5' => 11,
        ];

        $this->assertFalse(array_multiple_keys_exists([], ['theKey']));
        $this->assertFalse(array_multiple_keys_exists([], []));
        $this->assertFalse(array_multiple_keys_exists($array, ['one']));
        $this->assertTrue(array_multiple_keys_exists($array, ['k1']));
        $this->assertTrue(array_multiple_keys_exists($array, ['k1', 'k2']));
        $this->assertTrue(array_multiple_keys_exists($array, ['k1', 'x.break.deeper']));
    }

    /**
     * @test
     */
    public function should_return_array_keys()
    {
        $array = [
            'test' => 1,
            3,
            'nested' => [
                't' => 11,
                50,
                'g' => [
                    'p' => 5,
                    33
                ]
            ],
            100,

        ];

        $this->assertSame([
            'test' => 1,
            0 => 3,
            'nested.t' => 11,
            'nested.0' => 50,
            'nested.g.p' => 5,
            'nested.g.0' => 33,
            1 => 100,
        ], array_keys($array));
    }
}
