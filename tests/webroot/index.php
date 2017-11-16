<?php
/**
 * JBZoo PHPUnit
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    PHPUnit
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/PHPUnit
 * @author     Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\PHPunit;

require_once __DIR__ . '/../../vendor/autoload.php';

// Something complex
$testObj = (object)[
    'string' => ' 123 ',
    'int'    => 123,
    'float'  => 123.456,
    'null'   => null,
    'bool'   => true,
    'array'  => [1, 2, 3],
    'func'   => function () {
        echo 42;
    },
];

dump($testObj->int, 0, 'Some integer');
dump($testObj->float, 0, 'Some float');
dump($testObj->null, 0, 'Null');
dump($testObj->bool, 0, 'Some boolean');
dump($testObj->array, 0, 'Some array');
dump($testObj->string, 0, 'Some string');
dump($testObj, 0);
