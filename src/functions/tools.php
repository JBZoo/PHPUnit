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
 */

declare(strict_types=1);

namespace JBZoo\PHPUnit;

use JBZoo\HttpClient\HttpClient;
use JBZoo\HttpClient\Request;
use JBZoo\HttpClient\Response;
use JBZoo\Utils\Env;

/**
 * Check is current OS Windows
 *
 * @return bool
 */
function isWin(): bool
{
    return strncasecmp(PHP_OS_FAMILY, 'WIN', 3) === 0;
}

/**
 * @return bool
 */
function isTeamCity(): bool
{
    return Env::isExists('TEAMCITY_VERSION');
}

/**
 * @return bool
 */
function isTravis(): bool
{
    return Env::isExists('TRAVIS');
}

/**
 * @return bool
 */
function isPhpStorm(): bool
{
    return Env::isExists('IDE_PHPUNIT_CUSTOM_LOADER');
}

/**
 * Read file in binary save mode
 *
 * @param string $path
 * @return null|string
 */
function openFile(string $path): ?string
{
    $contents = null;

    $realPath = (string)realpath($path);
    if ('' !== $realPath && file_exists($realPath)) {
        $fileSize = (int)filesize($realPath);

        if ($fileSize > 0) {
            $handle = fopen($realPath, 'rb');
            if (false !== $handle) {
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
function httpRequest(string $url, $args = null, string $method = Request::GET, array $options = []): Response
{
    if (!class_exists(HttpClient::class)) {
        throw new Exception('jbzoo/http-client is required for httpRequest() function');
    }

    if (array_key_exists('timeout', $options)) {
        $options['timeout'] = 600; // For PHPUnit coverage
    }

    $client = new HttpClient($options);
    return $client->request($url, $args, $method, $options);
}

/**
 * @param bool $withNamespace
 * @return string|null
 */
function getTestName(bool $withNamespace = false): ?string
{
    $objects = debug_backtrace();
    $result = null;

    foreach ($objects as $object) {
        if (isset($object['object']) && $object['object'] instanceof PHPUnit) {
            $result = get_class($object['object']) . '__' . $object['function'];

            if (!$withNamespace) {
                $result = str_replace(__NAMESPACE__ . '\\', '', $result);
            }

            if (strpos($result, '__test') > 0) {
                break;
            }
        }
    }

    return $result;
}
