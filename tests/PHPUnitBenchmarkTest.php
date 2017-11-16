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

namespace JBZoo\PHPUnit;

use JBZoo\Profiler\Benchmark;

/**
 * Class PHPUnitBenchmarkTest
 *
 * @package JBZoo\PHPUnit
 */
class PHPUnitBenchmarkTest extends PHPUnit
{
    public function testBenchmarkMemory()
    {
        // Just try to execute it
        Benchmark::compare([
            'x1' => function () {
                return str_repeat(random_int(0, 9), 900000);
            },
            'x2' => function () {
                return str_repeat(random_int(0, 9), 900000 * 2);
            },
        ], ['name' => 'runBench()']);

        isTrue(true);
    }
}
