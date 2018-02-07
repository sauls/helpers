# function array_set_value(array &$array, $path, $value)

Writes a value into an associative array at the key path specified.

```php

$array = [
    'a' => ['b', 'c', 'd'],
    'v' => [
        'g' => 'h',
    ],
    'm' => 'value',
];

array_set_value($array, 'a', 1);

// <...>
// 'a' => 1,
// <...>

array_set_value($array, 'm.gg', ['aa', 'bb']);

// <...>
// 'm' => [
//    0 => 'value'
//    'gg' => [
//        'aa', 'bb',
//    ],         
// ]
// <...>

array_set_value($array, ['v', 'g'], 11);

// <...>
// 'v' => [
//    'g' => 11
// ]
// <...>

``` 

---
[← array_get_value](array-get-value.md) | [Index](../../Readme.md) | [class_has_ →]() 
