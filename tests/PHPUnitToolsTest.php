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

/**
 * Class PHPUnitToolsTest
 *
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
        isContain('application/json', $result->find('headers.content-type'));

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
        isSame(__METHOD__, getTestName(true));
        isContain(__FUNCTION__, getTestName(false));
        isContain('ToolsTest::testGetTestName', getTestName());
    }

    public function testIsWin()
    {
        isFalse(isWin());
    }
}
