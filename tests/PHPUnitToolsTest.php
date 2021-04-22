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

/**
 * Class PHPUnitToolsTest
 * @package JBZoo\PHPUnit
 */
class PHPUnitToolsTest extends PHPUnit
{
    public function testHttpRequest()
    {
        $uniq = uniqid('', true);
        $url = 'https://httpbin.org/post';
        $args = ['qwerty' => $uniq];

        $result = httpRequest($url, $args, 'post');

        isSame(200, $result->code);
        isContain('application/json', $result->getHeader('content-type'));

        $body = $result->getJSON();
        isContain('httpbin.org/post', $body->find('url'));
        isSame($body->find('form.qwerty'), $uniq);
    }

    public function testCliError()
    {
        skip('Some message...');
    }

    public function testGetTestName()
    {
        isSame(str_replace('::', '__', __METHOD__), getTestName(true));
        isContain(__FUNCTION__, getTestName(false));
        isContain('ToolsTest__testGetTestName', getTestName());
        isContain('ToolsTest__testGetTestName', getTestName(true));
        isContain('testGetTestName', getTestName(false));
    }

    public function testIsWin()
    {
        isFalse(isWin());
    }

    public function testDefines()
    {
        isSame(realpath(__DIR__ . '/..'), PROJECT_ROOT);
        isSame(realpath(__DIR__ . '/../src'), PROJECT_SRC);
        isSame(realpath(__DIR__ . '/../tests'), PROJECT_TESTS);
        isSame(realpath(__DIR__ . '/../build'), PROJECT_BUILD);
        isSame(true, JBZOO_PHPUNIT);
        isSame('/', DIRECTORY_SEPARATOR);
        isSame("\r\n", CRLF);
        isSame("\n", LF);
        isSame("\n", PHP_EOL);
    }
}
