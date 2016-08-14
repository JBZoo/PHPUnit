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
use JBZoo\Utils\Filter;
use JBZoo\Utils\FS;
use Symfony\Component\DomCrawler\Crawler;


/**** Controls ********************************************************************************************************/
//@codeCoverageIgnoreStart

/**
 * Skip current test
 * @param $msg
 */
function skip($msg = null)
{
    getTestCase()->markTestSkipped($msg);
}

/**
 * Incomplete current test
 * @param $msg
 */
function incomplete($msg = null)
{
    getTestCase()->markTestIncomplete($msg);
}

/**
 * Fail current test
 * @param $msg
 */
//@codeCoverageIgnoreStart
function fail($msg = null)
{
    getTestCase()->fail($msg);
}

/**
 * Success current test
 * @param $msg
 */
function success($msg = null)
{
    /** @noinspection PhpMethodParametersCountMismatchInspection */
    getTestCase()->isTrue(true, $msg);
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
    getTestCase()->assertEquals($expected, $actual, $msg);
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
    getTestCase()->assertNotEquals($expected, $actual, $msg);
    return $expected === $actual;
}

/**
 * @param array $testList
 * @param null  $msg
 * @return bool
 */
function isBatch($testList, $msg = null)
{
    $test = getTestCase();

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
    getTestCase()->assertTrue($value ? true : false, $msg);
    return $value ? true : false;
}

/**
 * @param bool $value
 * @param null $msg
 * @return bool
 */
function isFalse($value, $msg = null)
{
    getTestCase()->assertFalse($value ? true : false, $msg);
    return $value ? true : false;
}


/**
 * @param mixed  $expected
 * @param string $className
 * @param null   $msg
 */
function isClass($expected, $className, $msg = null)
{
    getTestCase()->assertInstanceOf($expected, $className, $msg);
}

/**
 * @param int   $expected
 * @param mixed $actual
 * @param null  $msg
 */
function isCount($expected, $actual, $msg = null)
{
    getTestCase()->assertCount($expected, $actual, $msg);
}


/**
 * @param string $pattern
 * @param string $value
 * @param null   $msg
 */
function isLike($pattern, $value, $msg = null)
{
    getTestCase()->assertRegExp($pattern, $value, $msg);
}

/**
 * @param string $pattern
 * @param string $value
 * @param null   $msg
 */
function isNotLike($pattern, $value, $msg = null)
{
    getTestCase()->assertNotRegExp($pattern, $value, $msg);
}

/**
 * @param string $filePathOrig
 * @param string $filePathCopy
 * @param null   $msg
 *
 */
function isFileEq($filePathOrig, $filePathCopy, $msg = null)
{
    getTestCase()->assertFileEquals($filePathOrig, $filePathCopy, $msg);
}

/**
 * @param $expected
 * @param $actual
 * @param $msg
 */
function isSame($expected, $actual, $msg = null)
{
    getTestCase()->assertSame($expected, $actual, $msg);
}

/**
 * @param mixed $expected
 * @param mixed $actual
 * @param null  $msg
 */
function isNotSame($expected, $actual, $msg = null)
{
    getTestCase()->assertNotSame($expected, $actual, $msg);
}

/**
 * @param mixed $expected
 */
function isNull($expected)
{
    getTestCase()->assertNull($expected);
}

/**
 * @param mixed $expected
 */
function isNotNull($expected)
{
    getTestCase()->assertNotNull($expected);
}

/**
 * @param mixed $expected
 * @param null  $msg
 */
function isEmpty($expected, $msg = null)
{
    getTestCase()->assertEmpty($expected, $msg);
}

/**
 * @param mixed $expected
 * @param null  $msg
 */
function isNotEmpty($expected, $msg = null)
{
    getTestCase()->assertNotEmpty($expected, $msg);
}

/**
 * @param string $key
 * @param array  $array
 * @param null   $msg
 */
function isKey($key, $array, $msg = null)
{
    getTestCase()->assertArrayHasKey($key, $array, $msg);
}

/**
 * @param string $key
 * @param array  $array
 * @param null   $msg
 */
function isNotKey($key, $array, $msg = null)
{
    getTestCase()->assertArrayNotHasKey($key, $array, $msg);
}

/**
 * Assert object has an attribute
 * @param string $attrName
 * @param mixed  $object
 */
function isAttr($attrName, $object)
{
    $test = getTestCase();
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
    $test = getTestCase();
    $test->assertNotNull($object, 'object ' . get_class($object) . ' is not empty');
    $test->assertObjectNotHasAttribute($attrName, $object);
}

/**
 * @param string $path
 * @param null   $msg
 */
function isDir($path, $msg = null)
{
    $test = getTestCase();
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
    getTestCase()->assertFileExists($path, $msg);
}

/**
 * @param string $path
 * @param null   $msg
 */
function isNotFile($path, $msg = null)
{
    $test = getTestCase();
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
    getTestCase()->assertContains($expected, $value, $msg, $ignoreCase);
}

/**
 * @param string $expected
 * @param string $value
 * @param bool   $ignoreCase
 * @param null   $msg
 */
function isNotContain($expected, $value, $ignoreCase = false, $msg = null)
{
    getTestCase()->assertNotContains($expected, $value, $msg, $ignoreCase);
}

/**
 * Is CSS selector find in the HTML code
 * @param string $html
 * @param string $selector
 * @param mixed  $expected
 * @param string $msg
 * @return bool
 * @throws Exception
 */
function isHtmlContain($html, $selector, $expected = null, $msg = null)
{
    if (!class_exists('\Symfony\Component\DomCrawler\Crawler')) {
        throw new Exception('symfony/dom-crawler required for isHtmlContain() function');
    }

    if (!class_exists('\Symfony\Component\CssSelector\CssSelectorConverter')) {
        throw new Exception('symfony/css-selector required for isHtmlContain() function');
    }

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
 * @param mixed  $expected
 * @param string $msg
 * @return bool
 * @throws Exception
 */
function isHtmlNotContain($html, $selector, $expected, $msg = null)
{
    if (!class_exists('\Symfony\Component\DomCrawler\Crawler')) {
        throw new Exception('symfony/dom-crawler required for isHtmlNotContain() function');
    }

    if (!class_exists('\Symfony\Component\CssSelector\CssSelectorConverter')) {
        throw new Exception('symfony/css-selector required for isHtmlNotContain() function');
    }

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
    getTestCase()->assertFileEquals($filePathOrig, $filePathCopy, $msg);
}

/**
 * @param $expected
 * @param $actual
 * @param $msg
 * @deprecated Use isSame(), renamed
 */
function same($expected, $actual, $msg = null)
{
    getTestCase()->assertSame($expected, $actual, $msg);
}

/**
 * @param mixed $expected
 * @param mixed $actual
 * @param null  $msg
 * @deprecated Use isNotSame(), renamed
 */
function notSame($expected, $actual, $msg = null)
{
    getTestCase()->assertNotSame($expected, $actual, $msg);
}

/**
 * Normilize paths and compare them
 *
 * @param string $expected
 * @param string $actual
 * @param string $message
 * @throws Exception
 */
function isSamePath($expected, $actual, $message = null)
{
    if (!class_exists('\JBZoo\Utils\Filter')) {
        throw new Exception('jbzoo/utils required for isSamePath() function');
    }

    $cleanFunc = function ($paths) {
        $return = array();
        $paths  = (array)$paths;
        foreach ($paths as $key => $path) {
            $return[$key] = FS::clean($path, '/');
        }
        return $return;
    };

    $expected = Filter::_($expected, $cleanFunc);
    $actual   = Filter::_($actual, $cleanFunc);

    isSame($expected, $actual, $message);
}
