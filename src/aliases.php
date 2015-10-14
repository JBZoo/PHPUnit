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

/* php 5.3.3 does not support this */
if (!defined('DEBUG_BACKTRACE_PROVIDE_OBJECT')) {
    define('DEBUG_BACKTRACE_PROVIDE_OBJECT', true);
}

/**
 * @return \PHPUnit_Framework_TestCase
 */
function getTestcase()
{
    $objects = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);

    foreach ($objects as $object) {
        if (isset($object['object']) && $object['object'] instanceof \PHPUnit_Framework_TestCase) {
            return $object['object'];
        }
    }
}

/**
 * @param mixed $expected
 * @param mixed $actual
 * @param null  $msg
 * @return bool
 */
function is($expected, $actual, $msg = null)
{
    getTestcase()->assertEquals($expected, $actual, $msg);
    return $expected === $actual ? true : false;
}

/**
 * @param array $testList
 * @param null  $msg
 * @return bool
 */
function isBatch($testList, $msg = null)
{
    $test = getTestcase();

    foreach ($testList as $testItem) {
        $test->assertEquals($testItem[0], $testItem[1], $msg);
    }
}

/**
 * @param bool $value
 * @param null $msg
 * @return bool
 */
function isTrue($value, $msg = null)
{
    getTestcase()->assertTrue($value ? true : false, $msg);
    return $value ? true : false;
}

/**
 * @param bool $value
 * @param null $msg
 * @return bool
 */
function isFalse($value, $msg = null)
{
    getTestcase()->assertFalse($value ? true : false, $msg);
    return $value ? true : false;
}


/**
 * @param mixed  $expected
 * @param string $сlassname
 * @param null   $msg
 */
function isClass($expected, $сlassname, $msg = null)
{
    getTestcase()->assertInstanceOf($expected, $сlassname, $msg);
}

/**
 * @param int   $expected
 * @param mixed $actual
 * @param null  $msg
 */
function isCount($expected, $actual, $msg = null)
{
    getTestcase()->assertCount($expected, $actual, $msg);
}

/**
 * Skip some test
 * @param $msg
 */
function skip($msg = null)
{
    getTestcase()->markTestSkipped($msg);
}

/**
 * @param string $pattern
 * @param string $value
 * @param null   $msg
 */
function isLike($pattern, $value, $msg = null)
{
    getTestcase()->assertRegExp($pattern, $value, $msg);
}

/**
 * @param string $pattern
 * @param string $value
 * @param null   $msg
 */
function isNotLike($pattern, $value, $msg = null)
{
    getTestcase()->assertNotRegExp($pattern, $value, $msg);
}

/**
 * @param string $filepath1
 * @param string $filepath2
 * @param null   $msg
 */
function fileEq($filepath1, $filepath2, $msg = null)
{
    getTestcase()->assertFileEquals($filepath1, $filepath2, $msg);
}

/**
 * @param $expected
 * @param $actual
 */
function same($expected, $actual)
{
    getTestcase()->assertSame($expected, $actual);
}

/**
 * @param mixed $expected
 */
function isNull($expected)
{
    getTestcase()->assertNull($expected);
}

/**
 * @param mixed $expected
 * @param null  $msg
 */
function isEmpty($expected, $msg = null)
{
    getTestcase()->assertEmpty($expected, $msg);
}

/**
 * @param string $key
 * @param array  $array
 * @param null   $msg
 */
function isKey($key, $array, $msg = null)
{
    getTestcase()->assertArrayHasKey($key, $array, $msg);
}

/**
 * Assert object has an attribute
 * @param string $attrName
 * @param mixed  $object
 */
function isAttr($attrName, $object)
{
    $test = getTestcase();
    $test->assertNotNull($object, "object " . get_class($object) . " is not empty");
    $test->assertObjectHasAttribute($attrName, $object);
}

/**
 * @param string $path
 * @param null   $msg
 */
function isDir($path, $msg = null)
{
    $test = getTestcase();
    $test->assertFileExists($path, $msg);
    $test->assertTrue(is_dir($path));
}

/**
 * @param string $path
 * @param null   $msg
 */
function isFile($path, $msg = null)
{
    getTestcase()->assertFileExists($path, $msg);
}

/**
 * @param string $expected
 * @param string $value
 * @param bool   $ignoreCase
 * @param null   $msg
 */
function isContain($expected, $value, $ignoreCase = false, $msg = null)
{
    getTestcase()->assertContains($expected, $value, $msg, $ignoreCase);
}

/**
 * @param string $expected
 * @param string $value
 * @param bool   $ignoreCase
 * @param null   $msg
 */
function isNotContain($expected, $value, $ignoreCase = false, $msg = null)
{
    getTestcase()->assertNotContains($expected, $value, $msg, $ignoreCase);
}
