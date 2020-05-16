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

declare(strict_types=1);

namespace JBZoo\PHPUnit;

use JBZoo\HttpClient\HttpClient;
use JBZoo\HttpClient\Response;
use JBZoo\Utils\Cli;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

/**
 * Check is current OS Windows
 *
 * @return bool
 */
function isWin()
{
    return strncasecmp(PHP_OS_FAMILY, 'WIN', 3) === 0;
}

/**
 * @param string $message
 * @param bool   $addEol
 */
function cliMessage($message, $addEol = true): void
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
 */
function cliError($message, $addEol = true): void
{
    $message = (string)$message;
    if ($addEol) {
        $message .= PHP_EOL;
    }

    MessageBuffer::getInstance()->error($message);
}

/**
 * Binary save to open file
 *
 * @param string $path
 * @return null|string
 */
function openFile($path)
{
    $contents = null;

    if ($realPath = realpath($path)) {
        $fileSize = (int)filesize($realPath);

        if ($fileSize > 0) {
            $handle = fopen($realPath, 'rb');
            if ($handle) {
                $contents = fread($handle, $fileSize);
                fclose($handle);
            }
        }
    }

    if ($contents === false) {
        $contents = null;
    }

    return $contents;
}

/**
 * @param string        $command
 * @param array<string> $args
 * @param null          $cwd
 * @return string
 * @throws Exception
 * @throws \JBZoo\Utils\Exception
 */
function cmd($command, $args = [], $cwd = null)
{
    if (!class_exists(Cli::class)) {
        throw new Exception('jbzoo/utils required for cmd() function');
    }

    if (!class_exists(Process::class)) {
        throw new Exception('symfony/process package required for cmd() function');
    }

    return Cli::exec($command, $args, $cwd);
}

/**
 * @param string                 $url
 * @param string|array<mixed>    $args
 * @param string                 $method
 * @param array<string|bool|int> $options
 * @return Response
 * @throws Exception
 * @throws \JBZoo\HttpClient\Exception
 */
function httpRequest(string $url, $args = null, $method = 'GET', array $options = [])
{
    if (!class_exists(HttpClient::class)) {
        throw new Exception('jbzoo/http-client required for httpRequest() function');
    }

    $client = new HttpClient($options);
    return $client->request($url, $args, $method, $options);
}

/**
 * @param bool $withNamespace
 * @return null|string
 */
function getTestName($withNamespace = false)
{
    $objects = debug_backtrace();
    $result = null;

    foreach ($objects as $object) {
        if (isset($object['object']) && $object['object'] instanceof TestCase) {
            $result = get_class($object['object']) . '::' . $object['function'];
            if (!$withNamespace) {
                $result = str_replace(__NAMESPACE__ . '\\', '', $result);
            }
            break;
        }
    }

    return $result;
}
