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
 * Class SimpleTest
 * @package JBZoo\PHPUnit\Benchmark
 */
class SimpleTest extends AbstractTest
{
    /**
     * @var \Closure
     */
    private $prepare = null;

    /**
     * @var \Closure
     */
    private $execute;

    /**
     * @var \Closure
     */
    private $cleanup = null;

    /**
     * @param          $name
     * @param \Closure $execute
     */
    public function __construct($name, \Closure $execute)
    {
        parent::__construct($name);

        $this->execute = $execute;
    }

    /**
     * @param \Closure $prepare
     * @return $this
     */
    public function setPrepare(\Closure $prepare)
    {
        $this->prepare = $prepare;

        return $this;
    }

    /**
     *
     */
    protected function prepare()
    {
        if ($prepare = $this->prepare) {
            $prepare();
        }
    }

    /**
     * @return mixed
     */
    protected function execute()
    {
        return call_user_func($this->execute);
    }

    /**
     * @param \Closure $cleanup
     * @return $this
     */
    public function setCleanup(\Closure $cleanup)
    {
        $this->cleanup = $cleanup;

        return $this;
    }

    /**
     *
     */
    protected function cleanup()
    {
        if ($cleanup = $this->cleanup) {
            $cleanup();
        }
    }
}
