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
    /**
     * @var array [Test]
     */
    private $_tests = array();

    /**
     * @var int
     */
    private $_count = null;

    /**
     * @var mixed
     */
    private $_overhead = null;

    /**
     * @param AbstractTest $test
     */
    public function addTest(AbstractTest $test)
    {
        $this->_tests[$test->getName()] = $test;
    }

    /**
     * Utility method to create tests on the fly. You may chain the test:
     *
     * @param string   $name
     * @param \Closure $closure function to execute
     * @return SimpleTest
     */
    public function add($name, \Closure $closure)
    {
        $test = new SimpleTest($name, $closure);
        $this->addTest($test);

        return $test;
    }

    /**
     * Runs an empty test to determine the benchmark overhead and run each test once
     */
    private function _warmup()
    {
        $warmup = new SimpleTest('warmup', function () {
        });
        $warmup->run();

        foreach ($this->_tests as $test) {
            $test->run();
        }

        $this->_overhead = $warmup->run($this->_count);
        $this->out(
            'PHP Overhead: ' .
            'time=' . round($this->_overhead['time'] * 1000, 2) . ' ms; ' .
            'memory=' . Util::convertToSI($this->_overhead['memory']) . ';' .
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

        if (null === $this->_count) {
            $this->maxSeconds(2); // aim for around 2 seconds per test
        }

        if ($output) {
            $this->out("Running tests {$this->_count} times");
        }

        $this->_warmup();

        $testNum = 0;
        foreach ($this->_tests as $name => $test) {
            if ($output) {
                $this->out('Testing ' . ++$testNum . '/' . count($this->_tests) . ' : ' . $name . ' ... ', false);
            }
            $results[$name] = $test->run($this->_count);

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
     * Average the guessCount of each test, determining the best n
     *
     * @param  float $maxSeconds
     * @return int
     */
    public function maxSeconds($maxSeconds)
    {
        if (!$this->_tests) {
            throw new \RuntimeException('No test in Benchmark.');
        }

        $minValue = INF;

        foreach ($this->_tests as $test) {
            $minValue = min($minValue, $test->guessCount($maxSeconds));
        }

        return $this->_count = Util::round($minValue);
    }

    /**
     * Output results in columns, padding right if values are string, left if numeric
     *
     * @param  array   $lines   array(array('Name' => 'Value'));
     * @param  integer $padding space between columns
     */
    public function outputTable(array $lines, $padding = 4)
    {
        if (!$lines) {
            return;
        }

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

            // pad left if numeric
            if (preg_match('/^[0-9]/', $line[$col])) {
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
            if ($testOne['time'] == $testTwo['time']) {
                return 0;
            } else {
                return ($testOne['time'] < $testTwo['time']) ? -1 : 1;
            }
        });

        $minTime   = INF;
        $minMemory = INF;

        foreach ($results as $name => $result) {
            $time = $result['time'];
            $time -= $this->_overhead['time']; // Substract base_time
            $time *= 1000; // Convert to ms
            $time = round($time);
            $time = max(1, $time); // min 1 ms

            $minTime = min($minTime, $time);

            $results[$name]['time'] = $time;

            if ($results[$name]['memory'] == 0) {
                $results[$name]['memory'] = -1;
            }

            $minMemory = min($minMemory, $results[$name]['memory']);
        }

        $output = array();

        foreach ($results as $name => $result) {
            $output[] = array(
                'Name of test' => $name,
                'Time, ms'     => number_format($result['time'], 0, '.', ' '),
                'Time, %'      => Util::relativePerc($minTime, $result['time']),
                'Memory'       => Util::convertToSI($result['memory']),
                'Memory, %'    => Util::relativePerc($minMemory, $result['memory']),
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
