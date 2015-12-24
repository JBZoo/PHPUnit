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

use JBZoo\PHPUnit\Benchmark\Benchmark;

/**
 * Class BenchmarkTest
 * @package JBZoo\PHPUnit
 */
class BenchmarkTest extends PHPUnit
{
    public function testTime()
    {
        cliMessage(PHP_EOL . '---------- Bench time');

        // Generate long string for tests
        $string = '';
        for ($i = 0; $i < 5000; $i++) {
            $string .= sha1(mt_rand(100000, 999999));
        }

        declare(ticks = 1);

        $benchmark = new Benchmark();
        $benchmark->add('md5', function () use ($string) {
            return md5($string);
        });

        $benchmark->add('sha1', function () use ($string) {
            return sha1($string);
        });

        $benchmark->add('crc32', function () use ($string) {
            return crc32($string);
        });

        $benchmark->setCount(500);
        $benchmark->run(true);
    }

    public function testMemory()
    {
        cliMessage(PHP_EOL . '---------- Bench memory');

        $benchmark = new Benchmark();

        declare(ticks = 1);

        $benchmark->add('Test #1', function () {
            return str_repeat('a', 1024 * 256);
        });

        $benchmark->add('Test #2', function () {
            return str_repeat('a', 1024 * 1024);
        });

        $benchmark->add('Test #3', function () {
            return str_repeat('a', 1024 * 1024 * 16);
        });

        $benchmark->setCount(500);

        $benchmark->run(true);
    }

    public function testBenchAlias()
    {
        runBench(array(
            'Test #1' => function () {
                $string = str_repeat(mt_rand(0, 9), 1024 * 1024);
                return md5($string);
            },
            'Test #2' => function () {
                $string = str_repeat(mt_rand(0, 9), 1024 * 512 * 16);
                return crc32($string);
            },
            'Test #3' => function () {
                $string = str_repeat(mt_rand(0, 9), 1024 * 256);
                return sha1($string);
            },
        ), array('count' => 500, 'time' => 1, 'name' => 'runBench function'));
    }

}
