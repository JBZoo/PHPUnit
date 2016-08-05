<?php
/**
 * JBZoo PHPUnit
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   PHPUnit
 * @license   MIT
 * @copyright Copyright (C) JBZoo.com,  All rights reserved.
 * @link      https://github.com/JBZoo/PHPUnit
 * @author    Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\PHPunit;

require_once __DIR__ . '/../../vendor/autoload.php';

$isCli = defined('STDOUT');

// For unit tests
if ($isCli) {
    if ($_SERVER['argc'] === 1) {
        dump($_SERVER['argv']); // exit code

    } elseif ($_SERVER['argc'] === 2) {
        dump($_SERVER['argv'], 0, 'cli arguments'); // no exit code
    }

} elseif (isset($_REQUEST['test']) && $_REQUEST['test'] === 'jbdump') {
    dump($_REQUEST['test-var']);
}


// Something complex
$testObj = (object)array(
    'string' => ' 123 ',
    'int'    => 123,
    'float'  => 123.456,
    'null'   => null,
    'bool'   => true,
    'array'  => array(1, 2, 3),
    'func'   => function () {
        echo 42;
    },
);

dump($testObj->int, 0, 'Some integer');
dump($testObj->float, 0, 'Some float');
dump($testObj->null, 0, 'Null');
dump($testObj->bool, 0, 'Some boolean');
dump($testObj->array, 0, 'Some array');
dump($testObj->string, 0, 'Some string');
dump($testObj, 0);
