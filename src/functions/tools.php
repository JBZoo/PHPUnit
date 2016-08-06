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

use JBZoo\HttpClient\HttpClient;
use JBZoo\HttpClient\Response;
use JBZoo\Utils\Cli;
use JBZoo\Utils\Sys;
use Symfony\Component\VarDumper\VarDumper;

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
 * @throws Exception
 *
 * @SuppressWarnings(PHPMD.ExitExpression)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function dump($var, $isDie = true, $label = '')
{
    if (!class_exists('\JBDump')) {
        throw new Exception('jbzoo/jbdump required for dump() function');
    }

    if (!class_exists('\Symfony\Component\VarDumper\VarDumper')) {
        throw new Exception('symfony/var-dumper required for dump() function');
    }

    $isCliMode = defined('STDOUT');

    // get trace mesage
    $trace     = debug_backtrace(false);
    $dirname   = pathinfo(dirname($trace[0]['file']), PATHINFO_BASENAME);
    $filename  = pathinfo($trace[0]['file'], PATHINFO_BASENAME);
    $line      = $trace[0]['line'];
    $callplace = "({$dirname}/{$filename}:{$line})";

    // output backtrace information
    $message = ($label ? '--- "' . $label . '" ---' : str_repeat('-', 20));
    $message = PHP_EOL . $message . ' ' . $callplace;

    if ($isCliMode) {
        fwrite(STDOUT, $message . PHP_EOL);

        $isSimpleVar = is_string($var) || is_numeric($var) || is_bool($var) || null === $var;
        if ($isSimpleVar) {
            ob_start();
            var_dump($var);
            $dump = ob_get_contents();
            ob_end_clean();

            fwrite(STDOUT, $dump);
        } else {
            putenv("ANSICON=on"); // Add colored output
            VarDumper::dump($var);
        }
    } elseif (class_exists('\JBDump')) {
        $jbdump = \JBDump::i(array(
            'log'      => array(
                'path' => PROJECT_ROOT . '/logs',
            ),
            'profiler' => array(
                'render'     => 4,
                'auto'       => 1,
                'showStart'  => 0,
                'showEnd'    => 0,
                'showOnAjax' => 1,
            ),
            'dump'     => array(
                'die'         => 0,
                'maxDepth'    => 5,
                'expandLevel' => 3,
            )
        ));

        if ($jbdump->isDebug()) {
            $jbdump->dump($var, $label, array('trace' => debug_backtrace()));
        }
    } else {
        echo $message . '<br/>';
        var_dump($var);
    }

    if ($isDie) {
        if ($isCliMode) {
            fwrite(STDOUT, 'Dump die!' . PHP_EOL);
            exit(255);
        } else {
            die('Dump die!' . PHP_EOL);
        }
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
 * @return bool
 */
function isXdebug()
{
    return Sys::hasXdebug();
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
        $filesize = filesize($realPath);

        if ($filesize > 0) {
            $handle   = fopen($realPath, 'rb');
            $contents = fread($handle, $filesize);
            fclose($handle);
        }
    }

    return $contents;
}

/**
 * @param string $command
 * @param array  $args
 * @param null   $cwd
 * @param bool   $verbose
 * @return string
 * @throws Exception
 */
function cmd($command, $args = array(), $cwd = null, $verbose = false)
{
    if (!class_exists('\JBZoo\Utils\Cli')) {
        throw new Exception('jbzoo/utils required for cmd() function');
    }

    if (!class_exists('\Symfony\Component\Process\Process')) {
        throw new Exception("symfony/process package required for cmd() function");
    }

    return Cli::exec($command, $args, $cwd, $verbose);
}

/**
 * @param string       $url
 * @param string|array $args
 * @param string       $method
 * @param array        $options
 * @return Response
 * @throws Exception
 */
function httpRequest($url, $args = null, $method = 'GET', array $options = array())
{
    if (!class_exists('\JBZoo\HttpClient\HttpClient')) {
        throw new Exception('jbzoo/http-client required for httpRequest() function');
    }

    $httClient = new HttpClient();
    return $httClient->request($url, $args, $method, $options);
}
