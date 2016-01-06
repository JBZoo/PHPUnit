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

/** @noinspection PhpUndefinedClassInspection */
use Symfony\Component\DomCrawler\Crawler;

/**
 * @return PHPUnit|null
 */
function getTestcase()
{
    $objects = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);

    foreach ($objects as $object) {
        if (isset($object['object']) && $object['object'] instanceof \PHPUnit_Framework_TestCase) {
            return $object['object'];
        }
    }

    //@codeCoverageIgnoreStart
    return null;
    //@codeCoverageIgnoreEnd
}

/**** Controls ********************************************************************************************************/
//@codeCoverageIgnoreStart

/**
 * Skip current test
 * @param $msg
 */
function skip($msg = null)
{
    getTestcase()->markTestSkipped($msg);
}

/**
 * Incomplete current test
 * @param $msg
 */
function incomplete($msg = null)
{
    getTestcase()->markTestIncomplete($msg);
}

/**
 * Fail current test
 * @param $msg
 */
//@codeCoverageIgnoreStart
function fail($msg = null)
{
    getTestcase()->fail($msg);
}

/**
 * Success current test
 * @param $msg
 */
function success($msg = null)
{
    /** @noinspection PhpMethodParametersCountMismatchInspection */
    getTestcase()->isTrue(true, $msg);
}

//@codeCoverageIgnoreEnd
/**** Asserts *********************************************************************************************************/

/**
 * @param mixed $expected
 * @param mixed $actual
 * @param null  $msg
 * @return bool
 *
 * @SuppressWarnings(PHPMD.ShortMethodName)
 */
function is($expected, $actual, $msg = null)
{
    getTestcase()->assertEquals($expected, $actual, $msg);
    return $expected === $actual;
}

/**
 * @param mixed $expected
 * @param mixed $actual
 * @param null  $msg
 * @return bool
 */
function isNot($expected, $actual, $msg = null)
{
    getTestcase()->assertNotEquals($expected, $actual, $msg);
    return $expected === $actual;
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
 * @param string $className
 * @param null   $msg
 */
function isClass($expected, $className, $msg = null)
{
    getTestcase()->assertInstanceOf($expected, $className, $msg);
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
 * @param string $filePathOrig
 * @param string $filePathCopy
 * @param null   $msg
 *
 */
function isFileEq($filePathOrig, $filePathCopy, $msg = null)
{
    getTestcase()->assertFileEquals($filePathOrig, $filePathCopy, $msg);
}

/**
 * @param $expected
 * @param $actual
 * @param $msg
 */
function isSame($expected, $actual, $msg = null)
{
    getTestcase()->assertSame($expected, $actual, $msg);
}

/**
 * @param mixed $expected
 * @param mixed $actual
 * @param null  $msg
 */
function isNotSame($expected, $actual, $msg = null)
{
    getTestcase()->assertNotSame($expected, $actual, $msg);
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
 */
function isNotNull($expected)
{
    getTestcase()->assertNotNull($expected);
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
 * @param mixed $expected
 * @param null  $msg
 */
function isNotEmpty($expected, $msg = null)
{
    getTestcase()->assertNotEmpty($expected, $msg);
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
 * @param string $key
 * @param array  $array
 * @param null   $msg
 */
function isNotKey($key, $array, $msg = null)
{
    getTestcase()->assertArrayNotHasKey($key, $array, $msg);
}

/**
 * Assert object has an attribute
 * @param string $attrName
 * @param mixed  $object
 */
function isAttr($attrName, $object)
{
    $test = getTestcase();
    $test->assertNotNull($object, 'object ' . get_class($object) . ' is not empty');
    $test->assertObjectHasAttribute($attrName, $object);
}

/**
 * Assert object has an attribute
 * @param string $attrName
 * @param mixed  $object
 */
function isNotAttr($attrName, $object)
{
    $test = getTestcase();
    $test->assertNotNull($object, 'object ' . get_class($object) . ' is not empty');
    $test->assertObjectNotHasAttribute($attrName, $object);
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
function isNotDir($path, $msg = null)
{
    if (is_dir($path)) {
        //@codeCoverageIgnoreStart
        fail("\"{$path}\" is direcory");
        //@codeCoverageIgnoreEnd
    } else {
        success($msg);
    }
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
 * @param string $path
 * @param null   $msg
 */
function isNotFile($path, $msg = null)
{
    $test = getTestcase();
    if (!is_dir($path)) {
        $test->assertFileNotExists($path, $msg);
    } else {
        success($msg);
    }
}

/**
 * @param string $expected
 * @param string $value
 * @param bool   $ignoreCase
 * @param null   $msg
 * @noinspection MoreThanThreeArgumentsInspection
 */
/** @noinspection MoreThanThreeArgumentsInspection */
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

/**
 * Is CSS selector find in the HTML code
 * @param string $html
 * @param string $selector
 * @param string $expected
 * @param string $msg
 * @return bool
 */
function isHtmlContain($html, $selector, $expected = null, $msg = null)
{
    $findText = null;

    try {
        $crawler  = new Crawler($html);
        $findText = $crawler->filter($selector)->text();
        isSame((string)$expected, (string)$findText);

    } catch (\Exception $exception) {

        if (!$expected) {
            success($msg);
        } else {
            //@codeCoverageIgnoreStart
            $msg = $msg ? $msg . ' // ' : '';
            fail($msg . 'Crawler: ' . $exception->getMessage());
            //@codeCoverageIgnoreEnd
        }

    }
}

/**
 * Is NOT find CSS-selector find in the HTML code
 * @param string $html
 * @param string $selector
 * @param string $expected
 * @param string $msg
 * @return bool
 */
function isHtmlNotContain($html, $selector, $expected, $msg = null)
{
    $findText = null;

    try {
        $crawler  = new Crawler($html);
        $findText = $crawler->filter($selector)->text();
        isNotSame((string)$expected, (string)$findText);

    } catch (\Exception $exception) {

        if (!$findText) {
            success($msg);

        } else {
            //@codeCoverageIgnoreStart
            $msg = $msg ? $msg . ' // ' : '';
            fail($msg . 'Crawler: ' . $exception->getMessage());
            //@codeCoverageIgnoreEnd
        }
    }
}

/**** Deprecated!******************************************************************************************************/

/**
 * @param string $filePathOrig
 * @param string $filePathCopy
 * @param null   $msg
 * @deprecated Use isFileEq(), renamed
 */
function fileEq($filePathOrig, $filePathCopy, $msg = null)
{
    getTestcase()->assertFileEquals($filePathOrig, $filePathCopy, $msg);
}

/**
 * @param $expected
 * @param $actual
 * @param $msg
 * @deprecated Use isSame(), renamed
 */
function same($expected, $actual, $msg = null)
{
    getTestcase()->assertSame($expected, $actual, $msg);
}

/**
 * @param mixed $expected
 * @param mixed $actual
 * @param null  $msg
 * @deprecated Use isNotSame(), renamed
 */
function notSame($expected, $actual, $msg = null)
{
    getTestcase()->assertNotSame($expected, $actual, $msg);
}
