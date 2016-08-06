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

use JBZoo\Profiler\Benchmark;

/**
 * Class BenchmarkTest
 * @package JBZoo\PHPUnit
 */
class BenchmarkTest extends PHPUnit
{
    public function testBenchmarkMemory()
    {
        Benchmark::compare(array(
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
}
