# function array_merge(... $arrays): array

Merges two or more arrays into one recursively.

```php
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

$result = array_merge($array1, $array2);

// $result === [
//     'a' => 25,
//     'b' => [
//         'c' => 'world',
//     ],
//     'c' => 111,// 
//     'v' => [
//         'u' => [
//             'd' => 'f',
//             't' => 23,
//         ],
//     ],
//     12 => 53,
//     11 => 'Yupi!',
//     13 => 49
// ]

```

---
[← Index](../../Readme.md) | [array_get_value →](array-get-value.md)
