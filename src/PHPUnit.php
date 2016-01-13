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
 * Class PHPUnit
 * @package      JBZoo\PHPUnit
 * @noinspection PhpUndefinedClassInspection
 * @codeCoverageIgnore
 */
abstract class PHPUnit extends \PHPUnit_Framework_TestCase
{
    /**
     * Start profiler
     * @deprecated See startProfiler()
     */
    public function startProfiler()
    {
        return startProfiler();
    }

    /**
     * Simple loop profiler
     * @param int       $count
     * @param bool|true $formated
     * @return array
     * @deprecated See loopProfiler()
     */
    public function loopProfiler($count = 1, $formated = true)
    {
        return loopProfiler($count, $formated);
    }


    /**
     * Get file list in directory
     * @param       $dir
     * @param null  $filter
     * @param array $results
     * @return array
     * @deprecated See getFileList()
     */
    public function getFileList($dir, $filter = null, &$results = array())
    {
        return getFileList($dir, $filter, $results);
    }

    /**
     * Binary save to open file
     * @param $path
     * @return null|string
     * @deprecated See openFile()
     */
    public function openFile($path)
    {
        return openFile($path);
    }

    /**
     * @param string $message
     * @param null   $label
     * @deprecated See alert()
     */
    public function showAlertMessage($message, $label = null)
    {
        alert($message, $label);
    }

    /**
     * @return bool
     * @deprecated
     */
    public static function isXdebug()
    {
        return isXdebug();
    }
}
