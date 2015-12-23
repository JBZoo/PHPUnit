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
    public function testDump()
    {
        $testObj = (object)array(
            'array'  => array(1, 2, 3),
            'bool'   => true,
            'string' => ' 123 ',
        );

        dump($testObj->array, 0);
        dump($testObj->bool, 0);
        dump($testObj, 0);
    }

    public function testBoolean()
    {
        isTrue(true);
        isFalse(false);
        isNull(null);
    }

    public function testEmpty()
    {
        isEmpty(0);
        isEmpty('');
        isEmpty(null);
        isEmpty('0');
        isEmpty(.0);
        isEmpty(array());
    }

    public function testEquals()
    {
        is(1, true);
        is(array(1, 2, 3), array(1, 2, 3));
        is(array('a' => 1, 'b' => 2), array('b' => 2, 'a' => 1));
        isNot(1, 2);

        same(array(1, 2, 3), array(1, 2, 3));
        notSame(array(1, 2, 3), array(3, 2, 1));

        isKey('test', array('test' => true));
        isAttr('test', (object)array('test' => true));

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
        fileEq(__FILE__, __FILE__);
        is($this->openFile(__FILE__), $this->openFile(__FILE__));

        isFile(__FILE__);
        isDir(__DIR__);

        isCount(4, $this->getFileList(__DIR__));
        isCount(1, $this->getFileList(__DIR__, 'aliases'));
        isCount(0, $this->getFileList(__DIR__, '\.qwerty$'));
        isCount(1, $this->getFileList(__DIR__ . '/..', '\.travis'));
    }

    public function testLoopProfiler()
    {
        $this->startProfiler();

        $max    = 100000;
        $result = array();
        for ($i = 0; $i < $max; $i++) {
            $result[] = $i;
        }

        alert($this->loopProfiler($max, true), 'Report');
        $this->loopProfiler($max, false); // just for coverage :)
    }

    public function testXdebug()
    {
        $this->isXDebug();
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
}
