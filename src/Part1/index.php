<?php

namespace Tutorial\Part1;

require_once __DIR__.'/../../vendor/autoload.php';

use Pimple\Container;

$container = new Container();

//Setting and getting elements
echo "-- Setting and getting elements --\n";

$container['my_key'] = 'my_value';

if (isset($container['my_key'])) {
    echo "'my_key' is set in the container\n";
}

echo sprintf("value of 'my_key': %s\n", $container['my_key']);

unset($container['my_key']);

echo sprintf("'my_key' is set after unset()? %s\n", isset($container['my_key']) ? 'yes' : 'no');

//Services
echo "\n\n-- Services --\n";

$container['my_obj'] = function ($c) {
    /* $c is the actual container, so you can access other services from here */
    echo "  in the body of 'my_obj'\n";
    $obj = new \stdClass();
    $obj->property = rand(0, 100);

    return $obj;
};

echo "getting 'my_obj'\n";

$obj1 = $container['my_obj'];

echo "\n";

var_dump($obj1);

echo "\ngetting 'my_obj' again\n";

$obj2 = $container['my_obj'];

$hash1 = spl_object_hash($obj1);
$hash2 = spl_object_hash($obj2);

echo sprintf(
    "the 2 objects are the same? %s (%s == %s)\n",
    $hash1 == $hash2 ? 'yes' : 'no',
    $hash1,
    $hash2
);

//Factory services
echo "\n\n-- Factory services --\n";

$container['my_factory'] = $container->factory(function ($c) {
    /* $c is the actual container, so you can access other services from here */
    echo "  in the body of 'my_obj'\n";
    $obj = new \stdClass();
    $obj->property = rand(0, 100);

    return $obj;
});

echo "getting 'my_factory'\n";

$obj1 = $container['my_factory'];
echo "\n";

var_dump($obj1);

echo "\ngetting 'my_factory' again\n";

$obj2 = $container['my_factory'];
echo "\n";

var_dump($obj2);

$hash1 = spl_object_hash($obj1);
$hash2 = spl_object_hash($obj2);

echo sprintf(
    "\nthe 2 objects are the same? %s (%s == %s)\n",
    $hash1 == $hash2 ? 'yes' : 'no',
    $hash1,
    $hash2
);

//Protected functions
echo "\n\n-- Protected functions --\n";

$container['my_macro'] = $container->protect(function ($from, $to) {
    return rand($from, $to);
});

echo sprintf("result of 'my_macro(20,30)': %d\n", $container['my_macro'](20, 30));
echo sprintf("result of 'my_macro(20,30)': %d\n", $container['my_macro'](20, 30));
echo sprintf("result of 'my_macro(20,30)': %d\n", $container['my_macro'](20, 30));

//Extending an existing service
echo "\n\n-- Extending an existing service --\n";

//here we cannot extend 'my_obj' as it contains an actual value(because it has been accessed)
//it's called a 'frozen service' and it cannot be extended
$container['my_obj2'] = function ($c) {
    /* $c is the actual container, so you can access other services from here */
    echo "  in the body of 'my_obj2'\n";
    $obj = new \stdClass();
    $obj->property = rand(0, 100);

    return $obj;
};

$container->extend('my_obj2', function ($obj, $c) {
    echo "  in the body of extended 'my_obj2'\n";
    $obj->property2 = 'new property';

    return $obj;
});

echo "getting 'my_obj2'\n";

$obj1 = $container['my_obj2'];

echo "\n";

var_dump($obj1);

echo "\ngetting 'my_obj2' again\n";

$obj2 = $container['my_obj2'];

$hash1 = spl_object_hash($obj1);
$hash2 = spl_object_hash($obj2);

echo sprintf(
    "the 2 objects are the same? %s (%s == %s)\n",
    $hash1 == $hash2 ? 'yes' : 'no',
    $hash1,
    $hash2
);
