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
    function testBase()
    {
        isTrue(true);
        isFalse(false);
        isNull(null);

        isEmpty(0);
        isEmpty('');
        isEmpty(null);
        isEmpty('0');
        isEmpty(.0);
        isEmpty(array());

        is(1, true);
        is(array(1, 2, 3), array(1, 2, 3));

        same(array(1, 2, 3), array(1, 2, 3));

        isKey('test', array('test' => true));
        isAttr('test', (object)array('test' => true));

        isBatch(array(
            array(1, 1),
            array(2, 2),
        ));

    }

    function testObjects()
    {
        isClass('stdClass', new \stdClass());
        isClass('\stdClass', new \stdClass());
        isClass('\JBZoo\PHPUnit\PHPUnit', $this);
        isClass('JBZoo\PHPUnit\PHPUnit', $this);
    }

    function testArray()
    {
        isCount(0, array());
        isCount(1, array(1));
        isCount(2, array(1, 3));
    }

    function testString()
    {
        isLike('#t.st#i', 'TESTO');
        isContain('t', 'test');
    }

    function testFilesystem()
    {
        fileEq(__FILE__, __FILE__);
        is($this->openFile(__FILE__), $this->openFile(__FILE__));

        isFile(__FILE__);
        isDir(__DIR__);

        isCount(2, $this->getFileList(__DIR__));
        isCount(1, $this->getFileList(__DIR__, 'aliases'));
        isCount(0, $this->getFileList(__DIR__, '\.qwerty$'));
        isCount(1, $this->getFileList(__DIR__ . '/..', '\.travis'));
    }

    function testLoopProfiler()
    {
        $this->startProfiler();

        $max    = 10000;
        $result = array();
        for ($i = 0; $i < $max; $i++) {
            $result[] = array($i);
        }

        $this->loopProfiler($max, true);
        $this->loopProfiler($max, false);
    }
}