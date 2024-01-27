<?php

/**
 * JBZoo Toolbox - PHPUnit.
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @see        https://github.com/JBZoo/PHPUnit
 */

declare(strict_types=1);

namespace JBZoo\PHPUnit;

use JBZoo\HttpClient\HttpClient;
use JBZoo\HttpClient\Request;
use JBZoo\HttpClient\Response;
use JBZoo\Utils\Env;

/**
 * Check is current OS Windows.
 */
function isWin(): bool
{
    return \strncasecmp(\PHP_OS_FAMILY, 'WIN', 3) === 0;
}

function isTeamCity(): bool
{
    return Env::isExists('TEAMCITY_VERSION');
}

function isTravis(): bool
{
    return Env::isExists('TRAVIS');
}

function isPhpStorm(): bool
{
    return Env::isExists('IDE_PHPUNIT_CUSTOM_LOADER');
}

/**
 * Read file in binary save mode.
 */
function openFile(string $path): ?string
{
    $contents = null;

    $realPath = (string)\realpath($path);
    if ($realPath !== '' && \file_exists($realPath)) {
        $fileSize = (int)\filesize($realPath);

        if ($fileSize > 0) {
            $handle = \fopen($realPath, 'r');
            if ($handle !== false) {
                $contents = \fread($handle, $fileSize);
                \fclose($handle);
            }
        }
    }

    if ($contents === false) {
        $contents = null;
    }

    return $contents;
}

/**
 * @param null|array<mixed>|string $args
 * @param array<bool|int|string>   $options
 * @codeCoverageIgnore
 */
function httpRequest(
    string $url,
    null|array|string $args = null,
    string $method = Request::GET,
    array $options = [],
): Response {
    if (!\class_exists(HttpClient::class)) {
        throw new Exception('jbzoo/http-client is required for httpRequest() function');
    }

    if (\array_key_exists('timeout', $options)) {
        $options['timeout'] = 600; // For PHPUnit coverage
    }

    return (new HttpClient($options))->request($url, $args, $method, $options);
}

function getTestName(bool $withNamespace = false): ?string
{
    $objects = \debug_backtrace();
    $result  = null;

    foreach ($objects as $object) {
        if (isset($object['object']) && $object['object'] instanceof PHPUnit) {
            $result = \get_class($object['object']) . '__' . $object['function'];

            if (!$withNamespace) {
                $result = \str_replace(__NAMESPACE__ . '\\', '', $result);
            }

            if (\strpos($result, '__test') > 0) {
                break;
            }
        }
    }

    return $result;
}
