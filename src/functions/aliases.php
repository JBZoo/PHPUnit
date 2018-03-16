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

use PHPUnit\Framework\Assert;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelectorConverter;

/**** Controls ********************************************************************************************************/
//@codeCoverageIgnoreStart

/**
 * Skip current test
 *
 * @param $msg
 */
function skip($msg)
{
    Assert::markTestSkipped($msg);
}

/**
 * Incomplete current test
 *
 * @param $msg
 */
function incomplete($msg)
{
    Assert::markTestIncomplete($msg);
}

/**
 * Fail current test
 *
 * @param $msg
 */
//@codeCoverageIgnoreStart
function fail($msg)
{
    Assert::fail($msg);
}

/**
 * Success current test
 *
 * @param $msg
 */
function success(string $msg = '')
{
    /** @noinspection PhpMethodParametersCountMismatchInspection */
    Assert::isTrue(true, $msg);
}

//@codeCoverageIgnoreEnd
/**** Asserts *********************************************************************************************************/

/**
 * @param mixed  $expected
 * @param mixed  $actual
 * @param string $msg
 * @return bool
 *
 * @SuppressWarnings(PHPMD.ShortMethodName)
 */
function is($expected, $actual, string $msg = '')
{
    Assert::assertEquals($expected, $actual, $msg);
    return $expected === $actual;
}

/**
 * @param mixed  $expected
 * @param mixed  $actual
 * @param string $msg
 * @return bool
 */
function isNot($expected, $actual, string $msg = '')
{
    Assert::assertNotEquals($expected, $actual, $msg);
    return $expected === $actual;
}

/**
 * @param array  $testList
 * @param string $msg
 * @return bool
 */
function isBatch($testList, string $msg = '')
{
    foreach ($testList as $testItem) {
        Assert::assertEquals($testItem[0], $testItem[1], $msg);
    }
}

/**
 * @param bool   $value
 * @param string $msg
 * @return bool
 */
function isTrue($value, string $msg = '')
{
    Assert::assertTrue($value ? true : false, $msg);
    return $value ? true : false;
}

/**
 * @param bool   $value
 * @param string $msg
 * @return bool
 */
function isFalse($value, string $msg = '')
{
    Assert::assertFalse($value ? true : false, $msg);
    return $value ? true : false;
}


/**
 * @param mixed  $expected
 * @param string $className
 * @param string $msg
 */
function isClass($expected, $className, string $msg = '')
{
    Assert::assertInstanceOf($expected, $className, $msg);
}

/**
 * @param int    $expected
 * @param mixed  $actual
 * @param string $msg
 */
function isCount($expected, $actual, string $msg = '')
{
    Assert::assertCount($expected, $actual, $msg);
}


/**
 * @param string $pattern
 * @param string $value
 * @param string $msg
 */
function isLike($pattern, $value, string $msg = '')
{
    Assert::assertRegExp($pattern, $value, $msg);
}

/**
 * @param string $pattern
 * @param string $value
 * @param string $msg
 */
function isNotLike($pattern, $value, string $msg = '')
{
    Assert::assertNotRegExp($pattern, $value, $msg);
}

/**
 * @param string $filePathOrig
 * @param string $filePathCopy
 * @param string $msg
 *
 */
function isFileEq($filePathOrig, $filePathCopy, string $msg = '')
{
    Assert::assertFileEquals($filePathOrig, $filePathCopy, $msg);
}

/**
 * @param $expected
 * @param $actual
 * @param $msg
 */
function isSame($expected, $actual, string $msg = '')
{
    Assert::assertSame($expected, $actual, $msg);
}

/**
 * @param mixed  $expected
 * @param mixed  $actual
 * @param string $msg
 */
function isNotSame($expected, $actual, string $msg = '')
{
    Assert::assertNotSame($expected, $actual, $msg);
}

/**
 * @param mixed $expected
 */
function isNull($expected)
{
    Assert::assertNull($expected);
}

/**
 * @param mixed $expected
 */
function isNotNull($expected)
{
    Assert::assertNotNull($expected);
}

/**
 * @param mixed  $expected
 * @param string $msg
 */
function isEmpty($expected, string $msg = '')
{
    Assert::assertEmpty($expected, $msg);
}

/**
 * @param mixed  $expected
 * @param string $msg
 */
function isNotEmpty($expected, string $msg = '')
{
    Assert::assertNotEmpty($expected, $msg);
}

/**
 * @param string $key
 * @param array  $array
 * @param string $msg
 */
function isKey($key, $array, string $msg = '')
{
    Assert::assertArrayHasKey($key, $array, $msg);
}

/**
 * @param string $key
 * @param array  $array
 * @param string $msg
 */
function isNotKey($key, $array, string $msg = '')
{
    Assert::assertArrayNotHasKey($key, $array, $msg);
}

/**
 * Assert object has an attribute
 *
 * @param string $attrName
 * @param mixed  $object
 */
function isAttr($attrName, $object)
{
    Assert::assertNotNull($object, 'object ' . get_class($object) . ' is not empty');
    Assert::assertObjectHasAttribute($attrName, $object);
}

/**
 * Assert object has an attribute
 *
 * @param string $attrName
 * @param mixed  $object
 */
function isNotAttr($attrName, $object)
{
    Assert::assertNotNull($object, 'object ' . get_class($object) . ' is not empty');
    Assert::assertObjectNotHasAttribute($attrName, $object);
}

/**
 * @param string $path
 * @param string $msg
 */
function isDir($path, string $msg = '')
{
    Assert::assertFileExists($path, $msg);
    Assert::assertTrue(is_dir($path));
}

/**
 * @param string $path
 * @param string $msg
 */
function isNotDir($path, string $msg = '')
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
 * @param string $msg
 */
function isFile($path, string $msg = '')
{
    Assert::assertFileExists($path, $msg);
}

/**
 * @param string $path
 * @param string $msg
 */
function isNotFile($path, string $msg = '')
{
    if (!is_dir($path)) {
        Assert::assertFileNotExists($path, $msg);
    } else {
        success($msg);
    }
}

/** @noinspection MoreThanThreeArgumentsInspection
 * @param string $expected
 * @param string $value
 * @param bool   $ignoreCase
 * @param string $msg
 */
function isContain($expected, $value, $ignoreCase = false, string $msg = '')
{
    Assert::assertContains($expected, $value, $msg, $ignoreCase);
}

/**
 * @param string $expected
 * @param string $value
 * @param bool   $ignoreCase
 * @param string $msg
 */
function isNotContain($expected, $value, $ignoreCase = false, string $msg = '')
{
    Assert::assertNotContains($expected, $value, $msg, $ignoreCase);
}

/**
 * Is CSS selector find in the HTML code
 *
 * @param string $html
 * @param string $selector
 * @param mixed  $expected
 * @param string $msg
 * @return bool
 * @throws Exception
 */
function isHtmlContain($html, $selector, $expected = null, string $msg = '')
{
    if (!class_exists(Crawler::class)) {
        throw new Exception('symfony/dom-crawler required for isHtmlContain() function'); // @codeCoverageIgnore
    }

    if (!class_exists(CssSelectorConverter::class)) {
        throw new Exception('symfony/css-selector required for isHtmlContain() function'); // @codeCoverageIgnore
    }

    $findText = null;

    try {
        $crawler = new Crawler($html);
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
 *
 * @param string $html
 * @param string $selector
 * @param mixed  $expected
 * @param string $msg
 * @return bool
 * @throws Exception
 */
function isHtmlNotContain($html, $selector, $expected, string $msg = '')
{
    if (!class_exists(Crawler::class)) {
        throw new Exception('symfony/dom-crawler required for isHtmlNotContain() function'); // @codeCoverageIgnore
    }

    if (!class_exists(CssSelectorConverter::class)) {
        throw new Exception('symfony/css-selector required for isHtmlNotContain() function'); // @codeCoverageIgnore
    }

    $findText = null;

    try {
        $crawler = new Crawler($html);
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

/**
 * @param string $mixedVar
 * @param string $msg
 * @return bool
 */
function isEmail($mixedVar, string $msg = '')
{
    isTrue(filter_var($mixedVar, FILTER_VALIDATE_EMAIL), $msg);
}

/**
 * @param string $mixedVar
 * @param string $msg
 * @return bool
 */
function isNotEmail($mixedVar, string $msg = '')
{
    isFalse(filter_var($mixedVar, FILTER_VALIDATE_EMAIL), $msg);
}

/**
 * @param string $date
 * @param int    $timeDiff
 * @param string $msg
 */
function isCurrentDate($date, $timeDiff = 300, string $msg = '')
{
    $nowDate = new \DateTime('now');
    $checkDate = new \DateTime($date);
    Assert::assertEquals($nowDate->getTimestamp(), $checkDate->getTimestamp(), $msg, $timeDiff);
}
