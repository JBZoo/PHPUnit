<?php

/**
 * JBZoo Toolbox - PHPUnit
 *
 * This file is part of the JBZoo Toolbox project.
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
 * Read file in binary save mode
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
        throw new Exception('jbzoo/http-client is required for httpRequest() function');
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
