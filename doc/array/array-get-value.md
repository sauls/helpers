# function array_get_value($array, $key, $default = null)

Retrieves the value of an array element or object property with the given key or property name.  

```php

class Demo {
    public $variable = 'array get value demo'
}

$array = [
    'a' => 'first element',
    'c' => [1, 2, 3, 4, 'string']
    'bb' => 23,
    'g' => [
        'h' => [
            'nested' => 'value'
            'some' => 'other value'
        ],
    ],
    'kk' => 11,
    'f' => [
        'd' => new Demo(),
        'v' => 'weee...'
    ]   
];

$result = array_get_value($array, 'nokey'); //  null
$result = array_get_value($array, 'nokey', 'no key found'); // no key found
$result = array_get_value($array, 'g.h.some'); // other value
$result = array_get_value($array, ['f', 'v']); // wee...
$result = array_get_value($array, 'f.d.variable'); // array get value demo
$result = array_get_value(
    $array, 
    function($array, $default) {
        return $array['bb'] + $array['kk'];
    }
); // 34
 
```

As you can see there is 

---
[← array_merge](array-merge.md) | [Index](../../Readme.md) | [array_set_value →](array-set-value.md)
