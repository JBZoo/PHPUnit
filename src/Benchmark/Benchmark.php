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
 * Class Benchmark
 * @package JBZoo\PHPUnit\Benchmark
 */
class Benchmark
{
    const COL_NAME       = 'Name of test';
    const COL_TIME       = 'Time';
    const COL_TIME_REL   = 'Time, %';
    const COL_MEMORY     = 'Memory';
    const COL_MEMORY_REL = 'Memory, %';

    /**
     * @var array [Test]
     */
    private $_tests = array();

    /**
     * @var int
     */
    private $_count = 1;

    /**
     * @var array
     */
    private $_overhead = array();

    /**
     * @param Test $test
     */
    public function addTest(Test $test)
    {
        $this->_tests[$test->getName()] = $test;
    }

    /**
     * Utility method to create tests on the fly. You may chain the test:
     *
     * @param string   $name
     * @param \Closure $closure function to execute
     * @return Test
     */
    public function add($name, \Closure $closure)
    {
        $test = new Test($name, $closure);
        $this->addTest($test);

        return $test;
    }

    /**
     * Runs an empty test to determine the benchmark overhead and run each test once
     */
    private function _warmup()
    {
        $warmup = new Test('warmup', function () {
        });

        $this->_overhead = $warmup->runTest($this->_count);

        // One call each method for init

        /** @var Test $test */
        foreach ($this->_tests as $test) {
            $test->runTest(1);
        }

        $this->out(
            'PHP Overhead: ' .
            'time=' . Util::timeFormat($this->_overhead['time']) . '; ' .
            'memory=' . Util::memFormat($this->_overhead['memory']) . ';' .
            PHP_EOL
        );
    }

    /**
     * @param bool|true $output
     * @return array
     */
    public function run($output = true)
    {
        $results = array();

        if ($output) {
            $this->out("Running tests {$this->_count} times");
        }

        $this->_warmup();

        $testNum = 0;

        /**
         * @var Test $test
         */
        foreach ($this->_tests as $name => $test) {
            if ($output) {
                $this->out('Testing ' . ++$testNum . '/' . count($this->_tests) . ' : ' . $name . ' ... ', false);
            }
            $results[$name] = $test->runTest($this->_count);

            $this->out('Done!');
        }

        $this->out('');

        if ($output) {
            $this->outputTable($this->formatResults($results));
        }

        return $results;
    }

    /**
     * @param $count
     */
    public function setCount($count)
    {
        $this->_count = $count;
    }

    /**
     * Output results in columns, padding right if values are string, left if numeric
     *
     * @param  array   $lines   array(array('Name' => 'Value'));
     * @param  integer $padding space between columns
     */
    public function outputTable(array $lines, $padding = 4)
    {
        $pad = function ($string, $width) use ($padding) {
            if ($width > 0) {
                return str_pad($string, $width, ' ') . str_repeat(' ', $padding);
            } else {
                return str_pad($string, -$width, ' ', STR_PAD_LEFT) . str_repeat(' ', $padding);
            }
        };

        // init width with keys' length
        $cols = array_combine(array_keys($lines[0]), array_map('strlen', array_keys($lines[0])));

        foreach ($cols as $col => $width) {

            foreach ($lines as $line) {
                $width = max($width, strlen($line[$col]));
            }

            if ($col !== self::COL_NAME) {
                $width = -$width;
            }

            $this->out($pad($col, $width), false);
            $cols[$col] = $width;
        }
        $this->out('');

        foreach ($lines as $line) {
            foreach ($cols as $col => $width) {
                $this->out($pad($line[$col], $width), false);
            }
            $this->out('');
        }
    }

    /**
     * Format the results, rounding numbers, showing difference percentages
     * and removing a flat time based on the benchmark overhead
     *
     * @param  array $results array($name => array('time' => 1.0))
     * @return array array(array('Test' => $name, 'Time' => '1000 ms', 'Perc' => '100 %'))
     */
    public function formatResults(array $results)
    {
        uasort($results, function ($testOne, $testTwo) {
            if ($testOne['time'] === $testTwo['time']) {
                return 0;
            } else {
                return ($testOne['time'] < $testTwo['time']) ? -1 : 1;
            }
        });

        $minTime   = INF;
        $minMemory = INF;

        foreach ($results as $name => $result) {
            // time
            $time = $result['time'];
            //$time -= $this->_overhead['time']; // Substract base_time
            $results[$name]['time'] = $time;

            $minTime = min($minTime, $time);

            // memory
            $memory = $results[$name]['memory'];
            $memory -= $this->_overhead['memory'];
            $results[$name]['memory'] = $memory;

            $minMemory = min($minMemory, $memory);
        }

        $output = array();

        foreach ($results as $name => $result) {
            $output[] = array(
                self::COL_NAME       => $name,
                self::COL_TIME       => Util::timeFormat($result['time']),
                self::COL_TIME_REL   => Util::relativePerc($minTime, $result['time']),
                self::COL_MEMORY     => Util::memFormat($result['memory']),
                self::COL_MEMORY_REL => Util::relativePerc($minMemory, $result['memory']),
            );
        }

        return $output;
    }

    /**
     * @param string $message
     * @param bool   $addEol
     */
    public function out($message, $addEol = true)
    {
        \JBZoo\PHPUnit\cliMessage($message, $addEol);
    }
}
