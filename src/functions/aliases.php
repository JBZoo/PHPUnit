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
function success($msg = null)
{
    /** @noinspection PhpMethodParametersCountMismatchInspection */
    Assert::isTrue(true, $msg);
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
    Assert::assertEquals($expected, $actual, $msg);
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
    Assert::assertNotEquals($expected, $actual, $msg);
    return $expected === $actual;
}

/**
 * @param array $testList
 * @param null  $msg
 * @return bool
 */
function isBatch($testList, $msg = null)
{
    foreach ($testList as $testItem) {
        Assert::assertEquals($testItem[0], $testItem[1], $msg);
    }
}

/**
 * @param bool $value
 * @param null $msg
 * @return bool
 */
function isTrue($value, $msg = null)
{
    Assert::assertTrue($value ? true : false, $msg);
    return $value ? true : false;
}

/**
 * @param bool $value
 * @param null $msg
 * @return bool
 */
function isFalse($value, $msg = null)
{
    Assert::assertFalse($value ? true : false, $msg);
    return $value ? true : false;
}


/**
 * @param mixed  $expected
 * @param string $className
 * @param null   $msg
 */
function isClass($expected, $className, $msg = null)
{
    Assert::assertInstanceOf($expected, $className, $msg);
}

/**
 * @param int   $expected
 * @param mixed $actual
 * @param null  $msg
 */
function isCount($expected, $actual, $msg = null)
{
    Assert::assertCount($expected, $actual, $msg);
}


/**
 * @param string $pattern
 * @param string $value
 * @param null   $msg
 */
function isLike($pattern, $value, $msg = null)
{
    Assert::assertRegExp($pattern, $value, $msg);
}

/**
 * @param string $pattern
 * @param string $value
 * @param null   $msg
 */
function isNotLike($pattern, $value, $msg = null)
{
    Assert::assertNotRegExp($pattern, $value, $msg);
}

/**
 * @param string $filePathOrig
 * @param string $filePathCopy
 * @param null   $msg
 *
 */
function isFileEq($filePathOrig, $filePathCopy, $msg = null)
{
    Assert::assertFileEquals($filePathOrig, $filePathCopy, $msg);
}

/**
 * @param $expected
 * @param $actual
 * @param $msg
 */
function isSame($expected, $actual, $msg = null)
{
    Assert::assertSame($expected, $actual, $msg);
}

/**
 * @param mixed $expected
 * @param mixed $actual
 * @param null  $msg
 */
function isNotSame($expected, $actual, $msg = null)
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
 * @param mixed $expected
 * @param null  $msg
 */
function isEmpty($expected, $msg = null)
{
    Assert::assertEmpty($expected, $msg);
}

/**
 * @param mixed $expected
 * @param null  $msg
 */
function isNotEmpty($expected, $msg = null)
{
    Assert::assertNotEmpty($expected, $msg);
}

/**
 * @param string $key
 * @param array  $array
 * @param null   $msg
 */
function isKey($key, $array, $msg = null)
{
    Assert::assertArrayHasKey($key, $array, $msg);
}

/**
 * @param string $key
 * @param array  $array
 * @param null   $msg
 */
function isNotKey($key, $array, $msg = null)
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
 * @param null   $msg
 */
function isDir($path, $msg = null)
{
    Assert::assertFileExists($path, $msg);
    Assert::assertTrue(is_dir($path));
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
    Assert::assertFileExists($path, $msg);
}

/**
 * @param string $path
 * @param null   $msg
 */
function isNotFile($path, $msg = null)
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
 * @param null   $msg
 */
function isContain($expected, $value, $ignoreCase = false, $msg = null)
{
    Assert::assertContains($expected, $value, $msg, $ignoreCase);
}

/**
 * @param string $expected
 * @param string $value
 * @param bool   $ignoreCase
 * @param null   $msg
 */
function isNotContain($expected, $value, $ignoreCase = false, $msg = null)
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
function isHtmlContain($html, $selector, $expected = null, $msg = null)
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
function isHtmlNotContain($html, $selector, $expected, $msg = null)
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
 * @param string $message
 * @return bool
 */
function isEmail($mixedVar, $message = null)
{
    isTrue(filter_var($mixedVar, FILTER_VALIDATE_EMAIL), $message);
}

/**
 * @param string $mixedVar
 * @param string $message
 * @return bool
 */
function isNotEmail($mixedVar, $message = null)
{
    isFalse(filter_var($mixedVar, FILTER_VALIDATE_EMAIL), $message);
}

/**
 * @param string $date
 * @param int    $timeDiff
 * @param string $message
 */
function isCurrentDate($date, $timeDiff = 300, $message = null)
{
    $nowDate = new \DateTime('now');
    $checkDate = new \DateTime($date);
    Assert::assertEquals($nowDate->getTimestamp(), $checkDate->getTimestamp(), $message, $timeDiff);
}
