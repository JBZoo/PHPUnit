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

use JBZoo\PHPUnit\Benchmark\Benchmark;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
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

    fwrite(STDOUT, $message . PHP_EOL);

    $isSimpleVar = is_string($var) || is_numeric($var) || is_bool($var) || null === $var;

    if ($isSimpleVar) {
        ob_start();
        var_dump($var);
        $dump = ob_get_contents();
        ob_end_clean();

        fwrite(STDOUT, $dump);

    } else {
        VarDumper::dump($var);
    }

    if ($isDie) {
        //@codeCoverageIgnoreStart
        fwrite(STDOUT, 'Dump die!' . PHP_EOL);
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

    MessageBuffer::getInstance()->info($message);
}

/**
 * @param string $message
 * @param bool   $addEol
 * @codeCoverageIgnore
 */
function cliError($message, $addEol = true)
{
    $message = (string)$message;
    if ($addEol) {
        $message .= PHP_EOL;
    }

    MessageBuffer::getInstance()->error($message);
}

/**
 * Show alert message
 * @param string $message
 * @param null   $label
 * @deprecated
 */
function alert($message, $label = null)
{
    if (!is_string($message)) {
        $message = print_r($message, true);
    }

    $message = PHP_EOL . ($label ? $label . ': ' : '') . $message;

    cliError($message);
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
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
function startProfiler()
{
    // cleanup and set first mark
    $GLOBALS['_jbzoo_profiler'] = array(
        'times'    => array(microtime(true)),
        'memories' => array(memory_get_usage(false)),
    );
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

    $_jbzoo_profiler['times'][]    = $time;
    $_jbzoo_profiler['memories'][] = $memory;

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

        if (!in_array($value, $_jbzoo_fileExcludes, true)) {
            if (is_dir($path)) {
                return getFileList($path, $filter, $results);

            } else {
                if ($filter) {

                    $regexp = '#' . $filter . '#u';
                    if (preg_match($regexp, $path)) {
                        $results[] = $path;
                    }

                } else {
                    $results[] = $path;
                }
            }
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
        $handle   = fopen($realPath, 'rb');
        $contents = fread($handle, filesize($realPath));
        fclose($handle);
    }

    return $contents;
}

/**
 * @link http://www.php.net/manual/en/control-structures.declare.php#control-structures.declare.ticks
 * @param array $tests
 * @param array $options
 */
function runBench(array $tests, array $options = array())
{
    $options = array_merge(array(
        'name'   => 'Compare speed',
        'count'  => 100,
        'output' => false,
    ), $options);

    if (!$options['output']) {
        ob_start();
    }

    $benchmark = new Benchmark();
    $benchmark->setCount($options['count']);

    declare(ticks = 1);

    startProfiler();
    $execCounter = $options['count'] * (count($tests) + 1); // "+1" to check PHP overhead

    foreach ($tests as $testName => $function) {
        $benchmark->add($testName, $function);
    }

    cliMessage(PHP_EOL . '-------------------- Start benchmark: ' . $options['name'] . ' --------------------');

    $benchmark->run(true);

    cliMessage(PHP_EOL . 'TOTAL ' . loopProfiler($execCounter));
    cliMessage('-------------------- Finish benchmark: ' . $options['name'] . ' --------------------');

    if (!$options['output']) {
        ob_end_clean();
    }
}

/**
 * @param string $command
 * @param array  $args
 * @param null   $cwd
 * @param bool   $verbose
 * @return string
 */
function cmd($command, $args = array(), $cwd = null, $verbose = false)
{
    $stringArgs  = array();
    $realCommand = $command;

    if (count($args) > 0) {

        foreach ($args as $key => $value) {
            $value = trim($value);
            $key   = trim($key);

            if (strpos($key, '-') !== 0) {
                if (strlen($key) == 1) {
                    $key = '-' . $key;
                } else {
                    $key = '--' . $key;
                }
            }

            if ($value) {
                $stringArgs[] = $key . '="' . addcslashes($value, '"') . '"';
            } else {
                $stringArgs[] = $key;
            }
        }
    }

    if (count($stringArgs)) {
        $realCommand = $command . ' ' . implode(' ', $stringArgs);
    }

    if ($cwd) {
        $cwd = realpath($cwd);
    }

    //@codeCoverageIgnoreStart
    if ($verbose) {
        cliMessage('Process: ' . $realCommand);
        cliMessage('CWD: ' . $cwd);
    }
    //@codeCoverageIgnoreEnd

    $process = new Process($realCommand, $cwd);
    $process->run();

    // executes after the command finishes
    //@codeCoverageIgnoreStart
    if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
    }
    //@codeCoverageIgnoreEnd

    return $process->getOutput();
}
