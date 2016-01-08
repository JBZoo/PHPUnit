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

namespace JBZoo\PHPUnit\Benchmark;

/**
 * Class Test
 * @package JBZoo\PHPUnit\Benchmark
 */
class Test
{
    /**
     * @var \Closure
     */
    private $_test;

    /**
     * @var string
     */
    private $_name;

    /**
     * @var Profiler
     */
    private $_profiler;

    /**
     * @param string   $name
     * @param \Closure $testFunction
     */
    public function __construct($name, \Closure $testFunction)
    {
        $this->_name     = $name;
        $this->_test     = $testFunction;
        $this->_profiler = new Profiler();
    }

    /**
     * @param int $count
     * @return array
     */
    public function runTest($count = 1)
    {
        gc_collect_cycles(); // clear memory before start

        $this->_profiler->start();

        for ($i = 0; $i < $count; $i++) {
            // Store the result so it appears in memory profiling
            $result = $this->_executeTest();
            unset($result);
        }

        $this->_profiler->stop();

        return array(
            'time'   => $this->_profiler->getTime(),
            'memory' => $this->_profiler->getMemoryUsage(),
            'count'  => $count,
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return mixed
     */
    protected function _executeTest()
    {
        return call_user_func($this->_test);
    }
}
