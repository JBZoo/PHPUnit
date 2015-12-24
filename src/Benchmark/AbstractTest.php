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
 * Class AbstractTest
 * @package JBZoo\PHPUnit
 */
abstract class AbstractTest
{
    /**
     * @var string
     */
    private $_name;

    /**
     * @var Profiler
     */
    private $_profiler;

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->_name     = $name;
        $this->_profiler = new Profiler;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param int $count
     * @return array
     */
    public function run($count = 1)
    {
        $this->prepare();

        gc_collect_cycles(); // clear memory before start

        $this->_profiler->start();

        for ($i = 0; $i < $count; $i++) {
            // Store the result so it appears in memory profiling
            $result = $this->execute();
            unset($result);
        }

        $this->_profiler->stop();

        $results = array(
            'time'   => $this->_profiler->getTime(),
            'memory' => $this->_profiler->getMemoryUsage(),
            'n'      => $count,
        );

        $this->cleanup();

        return $results;
    }

    /**
     * @return mixed
     */
    abstract protected function execute();

    /**
     * @param int $maxSeconds
     * @return float|int
     */
    public function guessCount($maxSeconds = 1)
    {
        $this->run(); // warmup
        $once = $this->run();

        if ($once['time'] >= $maxSeconds) {
            return 1;
        } else {
            return @round($maxSeconds / $once['time']);
        }
    }
}
