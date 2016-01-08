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

namespace JBZoo\PHPUnit;

/**
 * Class ToolsTest
 * @package JBZoo\PHPUnit
 */
class ToolsTest extends PHPUnit
{
    public function testBin()
    {
        $unique = uniqid('', true);

        $output = cmd('php bin/jbzoo-phpunit test', array(
            'option'  => $unique,
            'no-ansi' => '',
        ), PROJECT_ROOT);

        isContain('Success', $output);
        isContain($unique, $output);
    }

    public function testLoopProfiler()
    {
        startProfiler();

        $max    = 100000;
        $result = array();
        for ($i = 0; $i < $max; $i++) {
            $result[] = $i;
        }

        // just for coverage :)
        loopProfiler($max, true);
        loopProfiler($max, false);
    }

    public function testXdebug()
    {
        isXDebug();
    }

    public function testCmd()
    {
        $output = cmd('php', array('v' => ''));
        isContain('PHP', $output);

        $output = cmd('php', array('version' => ''));
        isContain('PHP', $output);
    }

    public function testDump()
    {
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

        if (0) { // hide
            dump($testObj->int, 0, 'Some integer');
            dump($testObj->float, 0, 'Some float');
            dump($testObj->null, 0, 'Null');
            dump($testObj->bool, 0, 'Some boolean');
            dump($testObj->array, 0, 'Some array');
        }

        dump($testObj->string, 0, 'Some string');
        dump($testObj, 0);
    }

    public function testCliError()
    {
        //cliError('Some error message');
    }
}
