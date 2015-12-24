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

/**
 * Class AliasesTest
 * @package JBZoo\PHPUnit
 */
class AliasesTest extends PHPUnit
{
    public function testBoolean()
    {
        isTrue(true);
        isFalse(false);

        isNull(null);
        isNotNull(false);
        isNotNull('');
        isNotNull(0);
        isNotNull(.0);
    }

    public function testEmpty()
    {
        isEmpty(0);
        isEmpty('');
        isEmpty(null);
        isEmpty('0');
        isEmpty(.0);
        isEmpty(array());

        isNotEmpty(array(0));
        isNotEmpty(true);
        isNotEmpty('1');
        isNotEmpty(1);
    }

    public function testEquals()
    {
        is(1, true);
        is(array(1, 2, 3), array(1, 2, 3));
        is(array('a' => 1, 'b' => 2), array('b' => 2, 'a' => 1));
        isNot(1, 2);

        isSame(array(1, 2, 3), array(1, 2, 3));
        isNotSame(array(1, 2, 3), array(3, 2, 1));

        isKey('test', array('test' => true));
        isNotKey('undefined', array('test' => true));

        isAttr('test', (object)array('test' => true));
        isNotAttr('undefined', (object)array('test' => true));

        isBatch(array(
            array(1, 1),
            array(2, 2),
        ));

    }

    public function testObjects()
    {
        isClass('stdClass', new \stdClass());
        isClass('\stdClass', new \stdClass());
        isClass('\JBZoo\PHPUnit\PHPUnit', $this);
        isClass('JBZoo\PHPUnit\PHPUnit', $this);
    }

    public function testArray()
    {
        isCount(0, array());
        isCount(1, array(1));
        isCount(2, array(1, 3));
    }

    public function testString()
    {
        isLike('#t.st#i', 'TESTO');
        isNotLike('#teeest#i', 'TESTO');

        isContain('t', 'test');
        isNotContain('x', 'test');
    }

    public function testFilesystem()
    {
        isFileEq(__FILE__, __FILE__);
        is(openFile(__FILE__), openFile(__FILE__));

        isFile(__FILE__);
        isDir(__DIR__);

        isNotFile(__DIR__);
        isNotFile(__FILE__ . '.qwerty');

        isNotDir(__FILE__);
        isNotDir(__DIR__ . '/qwerty');

        isCount(4, getFileList(__DIR__));
        isCount(1, getFileList(__DIR__, 'aliases'));
        isCount(0, getFileList(__DIR__, '\.qwerty$'));
        isCount(1, getFileList(__DIR__ . '/..', '\.travis'));
    }

    public function testLoopProfiler()
    {
        startProfiler();

        $max    = 100000;
        $result = array();
        for ($i = 0; $i < $max; $i++) {
            $result[] = $i;
        }

        alert(loopProfiler($max, true), 'Report');
        loopProfiler($max, false); // just for coverage :)
    }

    public function testXdebug()
    {
        isXDebug();
    }

    public function testSkip()
    {
        skip('Some reason to skip this test');
    }

    public function testAlert()
    {
        alert('Some alert message');
        alert(array('Some alert message'), 'Label');
    }

    public function testHtmlContain()
    {
        $html = '<body>
            <div class="test-class">
                <p>qwerty</p>
            </div>
            <span class="empty-1"> </span>
            <span class="empty-2"></span>
        </body>';

        isHtmlContain($html, 'body > div.test-class p', 'qwerty');
        isHtmlContain($html, 'body .empty-1', ' ');
        isHtmlContain($html, 'body .empty-2', '');

        isHtmlContain($html, 'body .empty-undefined', '', 'Empty string should be success');
        isHtmlContain($html, 'body .empty-undefined', null, 'NULL should be success');
        isHtmlContain($html, 'body .empty-undefined', false, 'FALSE should be success');
        isHtmlContain($html, 'body .empty-undefined', 0, 'FALSE should be success');
    }

    public function testHtmlNotContain()
    {
        $html = '<body>
            <div class="test-class">
                <p>qwerty</p>
            </div>
            <span class="empty-1"> </span>
            <span class="empty-2"></span>
        </body>';

        isHtmlNotContain($html, 'body > div.test-class p', 'qwerty-123');
        isHtmlNotContain($html, 'body .empty-1', 'qwerty');
        isHtmlNotContain($html, 'body .empty-2', ' ');

        isHtmlNotContain($html, 'body .empty-undefined', '');
        isHtmlNotContain($html, 'body .empty-undefined', ' ');
        isHtmlNotContain($html, 'body .empty-undefined', 123);
    }

    public function testDump()
    {
        $testObj = (object)array(
            'string' => ' 123 ',
            'int'    => 123,
            'float'  => 123.456,
            'null'   => null,
            'bool'   => true,
            'array'  => array(1, 2, 3),
            'func'   => function () {
                echo 42;
            },
        );

        dump($testObj->string, 0, 'Some string');
        dump($testObj->int, 0, 'Some integer');
        dump($testObj->float, 0, 'Some float');
        dump($testObj->null, 0, 'Null');
        dump($testObj->bool, 0, 'Some boolean');
        dump($testObj->array, 0, 'Some array');
        dump($testObj, 0);
    }
}
