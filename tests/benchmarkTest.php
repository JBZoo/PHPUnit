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
 * Class BenchmarkTest
 * @package JBZoo\PHPUnit
 */
class BenchmarkTest extends PHPUnit
{
    public function testBenchmarkMemory()
    {
        runBench(array(
            'x1'  => function () {
                return str_repeat(mt_rand(0, 9), 900000);
            },
            'x2'  => function () {
                return str_repeat(mt_rand(0, 9), 900000 * 2);
            },
            'x3'  => function () {
                return str_repeat(mt_rand(0, 9), 900000 * 3);
            },
            'x16' => function () {
                return str_repeat(mt_rand(0, 9), 900000 * 16);
            },
        ), array('name' => 'runBench()'));
    }

    public function testBenchmarkHash()
    {
        skip('Hash speed is not actual');

        runBench(array(
            'md5'   => function () {
                $string = str_repeat(mt_rand(0, 9), 1024 * 1024);
                return md5($string);
            },
            'crc32' => function () {
                $string = str_repeat(mt_rand(0, 9), 1024 * 1024);
                return crc32($string);
            },
            'sha1'  => function () {
                $string = str_repeat(mt_rand(0, 9), 1024 * 1024);
                return sha1($string);
            },
        ), array(
            'name'  => 'Hash',
            'count' => 100,
        ));
    }

    public function testBenchmarkEcho()
    {
        skip('Echo speed is not actual');

        runBench(array(
            'echo sem'    => function () {

                $a = str_repeat(mt_rand(0, 9), 32);
                $b = str_repeat(mt_rand(0, 9), 64);
                $c = str_repeat(mt_rand(0, 9), 128);
                $d = str_repeat(mt_rand(0, 9), 256);
                $e = str_repeat(mt_rand(0, 9), 512);
                $f = str_repeat(mt_rand(0, 9), 1024);

                echo $a, $b, $c, $d, $e, $f;
            },
            'echo cancat' => function () {

                $a = str_repeat(mt_rand(0, 9), 32);
                $b = str_repeat(mt_rand(0, 9), 64);
                $c = str_repeat(mt_rand(0, 9), 128);
                $d = str_repeat(mt_rand(0, 9), 256);
                $e = str_repeat(mt_rand(0, 9), 512);
                $f = str_repeat(mt_rand(0, 9), 1024);

                echo $a . $b . $c . $d . $e . $f;
            },
            'echo multi'  => function () {

                $a = str_repeat(mt_rand(0, 9), 32);
                $b = str_repeat(mt_rand(0, 9), 64);
                $c = str_repeat(mt_rand(0, 9), 128);
                $d = str_repeat(mt_rand(0, 9), 256);
                $e = str_repeat(mt_rand(0, 9), 512);
                $f = str_repeat(mt_rand(0, 9), 1024);

                echo $a;
                echo $b;
                echo $c;
                echo $d;
                echo $e;
                echo $f;
            },
        ), array(
            'name'   => 'echo',
            'count'  => 1000,
            'output' => false,
        ));
    }
}
