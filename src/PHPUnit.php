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
 * @package JBZoo\PHPUnit
 */
class PHPUnit extends \PHPUnit_Framework_TestCase
{
    protected static $times = array();
    protected static $memories = array();

    protected $excludeFiles = array(
        '.', '..', '.idea', '.git',
        'build', 'vendor', 'reports',
    );

    /**
     * Life hack - no test error
     */
    public function test()
    {
        // noop
    }

    /**
     * Start profiler
     */
    public function startProfiler()
    {
        // cleanup
        self::$times = self::$memories = array();

        // set firsy mark
        array_push(self::$times, microtime(true));
        array_push(self::$memories, memory_get_usage(false));
    }

    /**
     * Simple loop profiler
     * @param int       $count
     * @param bool|true $formated
     * @return array
     */
    public function loopProfiler($count = 1, $formated = true)
    {
        $time   = microtime(true);
        $memory = memory_get_usage(false);

        $timeDiff   = $time - end(self::$times);
        $memoryDiff = $memory - end(self::$memories);

        array_push(self::$times, $time);
        array_push(self::$memories, $memory);

        // build report
        $count = (int)abs($count);
        if ($formated) {
            $result = array(
                'time-diff' => number_format($timeDiff * 1000, 3, '.', ' ') . ' ms',
                'time-one'  => number_format($timeDiff * 1000 / $count, 3, '.', ' ') . ' ms',
                'memo-diff' => number_format($memoryDiff / 1024, 3, '.', ' ') . ' KB',
                'memo-one'  => number_format($memoryDiff / 1024 / $count, 3, '.', ' ') . ' KB',
            );
        } else {
            $result = array(
                'time-diff' => $timeDiff,
                'time-one'  => $timeDiff / $count,
                'memo-diff' => $memoryDiff,
                'memo-one'  => $memoryDiff / $count,
            );
        }

        return $result;
    }


    /**
     * Get file list in directory
     * @param       $dir
     * @param null  $filter
     * @param array $results
     * @return array
     */
    public function getFileList($dir, $filter = null, &$results = array())
    {
        $files = scandir($dir);

        foreach ($files as $value) {
            $path = $dir . DIRECTORY_SEPARATOR . $value;

            if (!is_dir($path) && !in_array($value, $this->excludeFiles, true)) {
                if ($filter) {
                    if (preg_match('#' . $filter . '#iu', $path)) {
                        $results[] = $path;
                    }
                } else {
                    $results[] = $path;
                }

            } elseif (is_dir($path) && !in_array($value, $this->excludeFiles, true)) {
                $this->getFileList($path, $filter, $results);
            }
        }

        return $results;
    }

    /**
     * Binary save to open file
     * @param $path
     * @return null|string
     */
    public function openFile($path)
    {
        $contents = null;

        if ($realPath = realpath($path)) {
            $handle   = fopen($realPath, "rb");
            $contents = fread($handle, filesize($realPath));
            fclose($handle);
        }

        return $contents;
    }

    /**
     * @param string $message
     * @param null   $label
     */
    public function showAlertMessage($message, $label = null)
    {
        if (!is_string($message)) {
            $message = print_r($message, true);
        }

        $message = ($label ? $label . ': ' : '') . $message . PHP_EOL;

        if (defined('STDOUT') && STDOUT) {
            fwrite(STDOUT, $message);
        } else {
            echo $message;
        }
    }

    /**
     * @return bool
     */
    public function isXDebug()
    {
        return extension_loaded('xdebug');
    }
}