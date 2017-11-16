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
        $url = 'http://httpbin.org/post';
        $args = ['qwerty' => $uniq];

        $result = httpRequest($url, $args, 'post');

        isSame(200, $result->code);
        isContain('application/json', $result->find('headers.content-type'));

        $body = $result->getJSON();
        isSame($body->find('url'), $url);
        isSame($body->find('form.qwerty'), $uniq);
    }

    public function testCmd()
    {
        $output = cmd('php', ['v' => '']);
        isContain('PHP', $output);

        $output = cmd('php', ['version' => '']);
        isContain('PHP', $output);
    }

    public function testCliError()
    {
        skip('Some message...');
        cliError('Some error message');
    }

    public function testGetTestName()
    {
        isSame(__METHOD__, getTestName(true));
        isContain(__FUNCTION__, getTestName(false));
        isContain('ToolsTest::testGetTestName', getTestName());
    }

    public function testHtml()
    {
        $html = '<body>
            <div class="test-class">
                <p>qwerty</p>
            </div>
            <span class="empty-1"> </span>
            <span class="empty-2"></span>
        </body>';

        isHtmlContain($html, 'body > div.test-class p', 'qwerty');
        isHtmlNotContain($html, 'body > div.test-class p', 'qwerty-123');
    }

    public function testIsWin()
    {
        isFalse(isWin());
    }

    public function testMessages()
    {
        cliMessage('cliMessage');
        cliError('cliError');
    }
}
