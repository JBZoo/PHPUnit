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

use Symfony\Component\VarDumper\VarDumper;


// @codingStandardsIgnoreFile
global $_jbzoo_profiler, $_jbzoo_fileExcludes; // Yes, this is not cool stuff...

$_jbzoo_profiler     = array();
$_jbzoo_fileExcludes = array(
    '.',
    '..',
    '.git',
    'build',
    'vendor',
    'resources',
);

/**
 * Check is current OS Windows
 * @return bool
 */
function isWin()
{
    return strncasecmp(PHP_OS, 'WIN', 3) === 0;
}

/**
 * Useful console dump
 * @param mixed  $var
 * @param bool   $isDie
 * @param string $label
 *
 * @SuppressWarnings(PHPMD.ExitExpression)
 */
function dump($var, $isDie = true, $label = '')
{
    // get trace mesage
    $trace     = debug_backtrace(false);
    $dirname   = pathinfo(dirname($trace[0]['file']), PATHINFO_BASENAME);
    $filename  = pathinfo($trace[0]['file'], PATHINFO_BASENAME);
    $line      = $trace[0]['line'];
    $callplace = "({$dirname}/{$filename}:{$line})";

    // output backtrace information
    $message = ($label ? '--- "' . $label . '" ---' : str_repeat('-', 20));
    $message = PHP_EOL . $message . ' ' . $callplace;

    cliMessage($message);

    $isSimpleVar = is_string($var) || is_numeric($var) || is_bool($var) || is_null($var);

    if ($isSimpleVar) {
        ob_start();
        var_dump($var);
        $dump = ob_get_contents();
        ob_end_clean();
        cliMessage($dump, false);

    } else {
        VarDumper::dump($var);
    }

    if ($isDie) {
        //@codeCoverageIgnoreStart
        cliError('Dump die!');
        exit(255);
        //@codeCoverageIgnoreEnd
    }
}

/**
 * @param string $message
 * @param bool   $addEol
 */
function cliMessage($message, $addEol = true)
{
    $message = (string)$message;
    if ($addEol) {
        $message .= PHP_EOL;
    }

    if (defined('STDOUT')) {
        fwrite(STDOUT, $message);
    } else {
        echo $message;
    }
}

/**
 * @param string $message
 * @param bool   $addEol
 */
function cliError($message, $addEol = true)
{
    $message = (string)$message;
    if ($addEol) {
        $message .= PHP_EOL;
    }

    if (defined('STDERR')) {
        fwrite(STDERR, $message);
    } else {
        echo $message;
    }
}

/**
 * @return bool
 */
function isXdebug()
{
    return extension_loaded('xdebug');
}

/**
 * Start profiler
 */
function startProfiler()
{
    global $_jbzoo_profiler;

    // cleanup
    $_jbzoo_profiler = array(
        'times'    => array(),
        'memories' => array(),
    );

    // set first mark
    array_push($_jbzoo_profiler['times'], microtime(true));
    array_push($_jbzoo_profiler['memories'], memory_get_usage(false));
}

/**
 * Simple loop profiler
 * @param int       $count
 * @param bool|true $formated
 * @return array
 */
function loopProfiler($count = 1, $formated = true)
{
    global $_jbzoo_profiler;

    $time   = microtime(true);
    $memory = memory_get_usage(false);

    $_timeDiff   = $time - end($_jbzoo_profiler['times']);
    $_memoryDiff = $memory - end($_jbzoo_profiler['memories']);

    array_push($_jbzoo_profiler['times'], $time);
    array_push($_jbzoo_profiler['memories'], $memory);

    // build report
    $count = (int)abs($count);
    if ($formated) {
        $timeDiff = number_format($_timeDiff * 1000, 2, '.', ' ') . ' ms';
        $timeOne  = number_format($_timeDiff * 1000 / $count, 2, '.', ' ') . ' ms';
        $memoDiff = number_format($_memoryDiff / 1024, 2, '.', ' ') . ' KB';
        $memoOne  = number_format($_memoryDiff / 1024 / $count, 2, '.', ' ') . ' KB';
        $count    = number_format($count, 0, '', ' ');

        $result = implode(';   ', array(
            'TIME: ' . $timeDiff . '/' . $timeOne,
            'MEMO: ' . $memoDiff . '/' . $memoOne,
            'COUNT: ' . $count,
        ));

    } else {
        $result = array(
            'time-diff' => $_timeDiff,
            'time-one'  => $_timeDiff / $count,
            'memo-diff' => $_memoryDiff,
            'memo-one'  => $_memoryDiff / $count,
            'count'     => $count,
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
function getFileList($dir, $filter = null, &$results = array())
{
    global $_jbzoo_fileExcludes;

    $files = scandir($dir);
    foreach ($files as $value) {
        $path = $dir . DIRECTORY_SEPARATOR . $value;

        $path = realpath($path);

        if (!is_dir($path) && !in_array($value, $_jbzoo_fileExcludes, true)) {
            if ($filter) {

                $regexp = '#' . $filter . '#u';
                if (preg_match($regexp, $path)) {
                    $results[] = $path;
                }

            } else {
                $results[] = $path;
            }

        } elseif (is_dir($path) && !in_array($value, $_jbzoo_fileExcludes, true)) {
            return getFileList($path, $filter, $results);
        }
    }

    return $results;
}

/**
 * Binary save to open file
 * @param $path
 * @return null|string
 */
function openFile($path)
{
    $contents = null;

    if ($realPath = realpath($path)) {
        $handle   = fopen($realPath, "rb");
        $contents = fread($handle, filesize($realPath));
        fclose($handle);
    }

    return $contents;
}
