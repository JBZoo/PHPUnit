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

use DateTime;
use PHPUnit\Framework\Assert;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\Crawler;

/**** Controls ********************************************************************************************************/
/**
 * Skip current test
 *
 * @param string $msg
 */
function skip(string $msg): void
{
    Assert::markTestSkipped($msg);
}

/**
 * Incomplete current test
 *
 * @param string $msg
 */
function incomplete(string $msg): void
{
    Assert::markTestIncomplete($msg);
}

/**
 * Fail current test
 *
 * @param string $msg
 */
function fail(string $msg = ''): void
{
    Assert::fail($msg);
}

/**
 * Success current test
 *
 * @param string $msg
 */
function success(string $msg = ''): void
{
    isTrue(true, $msg);
}

/**** Asserts *********************************************************************************************************/

/**
 * @param mixed  $expected
 * @param mixed  $actual
 * @param string $msg
 *
 * @SuppressWarnings(PHPMD.ShortMethodName)
 */
function is($expected, $actual, string $msg = ''): void
{
    Assert::assertEquals($expected, $actual, $msg);
}

/**
 * @param mixed  $expected
 * @param mixed  $actual
 * @param string $msg
 */
function isNot($expected, $actual, string $msg = ''): void
{
    Assert::assertNotEquals($expected, $actual, $msg);
}

/**
 * @param array  $testList
 * @param string $msg
 */
function isBatch($testList, string $msg = ''): void
{
    foreach ($testList as $testItem) {
        Assert::assertEquals($testItem[0], $testItem[1], $msg);
    }
}

/**
 * @param bool   $value
 * @param string $msg
 */
function isTrue($value, string $msg = ''): void
{
    Assert::assertTrue($value ? true : false, $msg);
}

/**
 * @param bool   $value
 * @param string $msg
 */
function isFalse($value, string $msg = ''): void
{
    Assert::assertFalse($value ? true : false, $msg);
}


/**
 * @param string $expected
 * @param mixed  $className
 * @param string $msg
 */
function isClass($expected, $className, string $msg = ''): void
{
    Assert::assertInstanceOf($expected, $className, $msg);
}

/**
 * @param int    $expected
 * @param mixed  $actual
 * @param string $msg
 */
function isCount($expected, $actual, string $msg = ''): void
{
    Assert::assertCount($expected, $actual, $msg);
}


/**
 * @param string $pattern
 * @param string $value
 * @param string $msg
 */
function isLike($pattern, $value, string $msg = ''): void
{
    Assert::assertRegExp($pattern, $value, $msg);
}

/**
 * @param string $pattern
 * @param string $value
 * @param string $msg
 */
function isNotLike($pattern, $value, string $msg = ''): void
{
    Assert::assertNotRegExp($pattern, $value, $msg);
}

/**
 * @param string $filePathOrig
 * @param string $filePathCopy
 * @param string $msg
 *
 */
function isFileEq($filePathOrig, $filePathCopy, string $msg = ''): void
{
    Assert::assertFileEquals($filePathOrig, $filePathCopy, $msg);
}

/**
 * @param mixed  $expected
 * @param mixed  $actual
 * @param string $msg
 */
function isSame($expected, $actual, string $msg = ''): void
{
    Assert::assertSame($expected, $actual, $msg);
}

/**
 * @param mixed  $expected
 * @param mixed  $actual
 * @param string $msg
 */
function isNotSame($expected, $actual, string $msg = ''): void
{
    Assert::assertNotSame($expected, $actual, $msg);
}

/**
 * @param mixed $expected
 */
function isNull($expected): void
{
    Assert::assertNull($expected);
}

/**
 * @param mixed $expected
 */
function isNotNull($expected): void
{
    Assert::assertNotNull($expected);
}

/**
 * @param mixed  $expected
 * @param string $msg
 */
function isEmpty($expected, string $msg = ''): void
{
    Assert::assertEmpty($expected, $msg);
}

/**
 * @param mixed  $expected
 * @param string $msg
 */
function isNotEmpty($expected, string $msg = ''): void
{
    Assert::assertNotEmpty($expected, $msg);
}

/**
 * @param string $key
 * @param array  $array
 * @param string $msg
 */
function isKey($key, $array, string $msg = ''): void
{
    Assert::assertArrayHasKey($key, $array, $msg);
}

/**
 * @param string $key
 * @param array  $array
 * @param string $msg
 */
function isNotKey($key, $array, string $msg = ''): void
{
    Assert::assertArrayNotHasKey($key, $array, $msg);
}

/**
 * Assert object has an attribute
 *
 * @param string $attrName
 * @param mixed  $object
 * @param string $msg
 */
function isAttr($attrName, $object, string $msg = ''): void
{
    Assert::assertNotNull($object, 'object ' . get_class($object) . " is not empty. {$msg}");
    Assert::assertObjectHasAttribute($attrName, $object, $msg);
}

/**
 * Assert object has an attribute
 *
 * @param string $attrName
 * @param mixed  $object
 * @param string $msg
 */
function isNotAttr($attrName, $object, string $msg = ''): void
{
    Assert::assertNotNull($object, 'object ' . get_class($object) . " is not empty. {$msg}");
    Assert::assertObjectNotHasAttribute($attrName, $object, $msg);
}

/**
 * @param string $path
 * @param string $msg
 */
function isDir($path, string $msg = ''): void
{
    Assert::assertFileExists($path, $msg);
    Assert::assertDirectoryExists($path);
}

/**
 * @param string $path
 * @param string $msg
 */
function isNotDir($path, string $msg = ''): void
{
    if (is_dir($path)) {
        fail("\"{$path}\" is directory");
    } else {
        success($msg);
    }
}

/**
 * @param string $path
 * @param string $msg
 */
function isFile($path, string $msg = ''): void
{
    Assert::assertFileExists($path, $msg);
}

/**
 * @param string $path
 * @param string $msg
 */
function isNotFile($path, string $msg = ''): void
{
    if (!is_dir($path)) {
        Assert::assertFileNotExists($path, $msg);
    } else {
        success($msg);
    }
}

/**
 * @param string $expected
 * @param string $value
 * @param bool   $ignoreCase
 * @param string $msg
 */
function isContain($expected, $value, $ignoreCase = false, string $msg = ''): void
{
    if ($ignoreCase) {
        Assert::assertStringContainsStringIgnoringCase($expected, $value, $msg);
    } else {
        Assert::assertStringContainsString($expected, $value, $msg);
    }
}

/**
 * @param string $expected
 * @param string $value
 * @param bool   $ignoreCase
 * @param string $msg
 */
function isNotContain($expected, $value, $ignoreCase = false, string $msg = ''): void
{
    if ($ignoreCase) {
        Assert::assertStringNotContainsStringIgnoringCase($expected, $value, $msg);
    } else {
        Assert::assertStringNotContainsString($expected, $value, $msg);
    }
}

/**
 * Is CSS selector find in the HTML code
 *
 * @param string $html
 * @param string $selector
 * @param mixed  $expected
 * @param string $msg
 * @throws Exception
 */
function isHtmlContain($html, $selector, $expected = null, string $msg = ''): void
{
    if (!class_exists(Crawler::class)) {
        throw new Exception('symfony/dom-crawler required for isHtmlContain() function');
    }

    if (!class_exists(CssSelectorConverter::class)) {
        throw new Exception('symfony/css-selector required for isHtmlContain() function');
    }

    $findText = null;

    try {
        $crawler = new Crawler($html);
        $findText = $crawler->filter($selector)->text();
        isSame((string)$expected, $findText);
    } catch (\Exception $exception) {
        if (!$expected) {
            success($msg);
        } else {
            $msg = $msg ? $msg . ' // ' : '';
            fail($msg . 'Crawler: ' . $exception->getMessage());
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
 * @throws Exception
 */
function isHtmlNotContain($html, $selector, $expected, string $msg = ''): void
{
    if (!class_exists(Crawler::class)) {
        throw new Exception('symfony/dom-crawler required for isHtmlNotContain() function');
    }

    if (!class_exists(CssSelectorConverter::class)) {
        throw new Exception('symfony/css-selector required for isHtmlNotContain() function');
    }

    $findText = null;

    try {
        $crawler = new Crawler($html);
        $findText = $crawler->filter($selector)->text();
        isNotSame((string)$expected, $findText);
    } catch (\Exception $exception) {
        if (!$findText) {
            success($msg);
        } else {
            $msg = $msg ? $msg . ' // ' : '';
            fail($msg . 'Crawler: ' . $exception->getMessage());
        }
    }
}

/**
 * @param string $mixedVar
 * @param string $msg
 */
function isEmail($mixedVar, string $msg = ''): void
{
    isTrue(filter_var($mixedVar, FILTER_VALIDATE_EMAIL), $msg);
}

/**
 * @param string $mixedVar
 * @param string $msg
 */
function isNotEmail($mixedVar, string $msg = ''): void
{
    isFalse(filter_var($mixedVar, FILTER_VALIDATE_EMAIL), $msg);
}

/**
 * @param string $date
 * @param int    $timeDiff
 * @param string $msg
 * @throws \Exception
 */
function isCurrentDate($date, $timeDiff = 300, string $msg = ''): void
{
    $nowDate = new DateTime('now');
    $checkDate = new DateTime($date);
    Assert::assertEqualsWithDelta($nowDate->getTimestamp(), $checkDate->getTimestamp(), $timeDiff, $msg);
}

/**
 * @param float|array $expected
 * @param float|array $actual
 * @param string      $msg
 * @param float       $allowableDiff
 */
function isAmount($expected, $actual, string $msg = '', $allowableDiff = 0.03): void
{
    if (\is_array($expected) || \is_array($actual)) {
        $msg = $msg ?: 'Actual diff=' . ((float)$expected[0] - (float)$actual[0])
            . "; Expected diff={$allowableDiff}";
        Assert::assertEqualsWithDelta((float)$expected[0], (float)$actual[0], $allowableDiff, $msg);
        isSame($expected[1], $actual[1], $msg);
    } else {
        $msg = $msg ?: 'Diff: ' . ((float)$expected - (float)$actual)
            . "; Expected diff={$allowableDiff}";
        Assert::assertEqualsWithDelta((float)$expected, (float)$actual, $allowableDiff, $msg);
    }
}

/**
 * @param mixed  $expected
 * @param mixed  $actual
 * @param string $msg
 * @param float  $allowableDiff
 */
function isNotAmount($expected, $actual, string $msg = '', $allowableDiff = 0.03): void
{
    if (\is_array($expected) || \is_array($actual)) {
        $msg = $msg ?: 'Actual diff=' . ((float)$expected[0] - (float)$actual[0])
            . "; Expected diff={$allowableDiff}";
        Assert::assertNotEqualsWithDelta((float)$expected[0], (float)$actual[0], $allowableDiff, $msg);
        isSame($expected[1], $actual[1], $msg);
    } else {
        $msg = $msg ?: 'Diff: ' . ((float)$expected - (float)$actual)
            . "; Expected diff={$allowableDiff}";
        Assert::assertNotEqualsWithDelta((float)$expected, (float)$actual, $allowableDiff, $msg);
    }
}

/**
 * @param string $date1
 * @param string $date2
 * @param int    $timeDiff
 * @param string $msg
 * @throws \Exception
 */
function isDiffBetweenDates($date1, $date2, $timeDiff = 300, string $msg = ''): void
{
    $dateObj1 = new \DateTime($date1);
    $dateObj2 = new \DateTime($date2);
    isTrue(
        abs((int)$dateObj1->getTimestamp() - (int)$dateObj2->getTimestamp()) === $timeDiff,
        "Diff between dates: {$date1} and {$date2} is more then {$timeDiff} seconds. {$msg}"
    );
}

/**
 * @param string $expected
 * @param string $actual
 * @param string $format
 * @param string $msg
 * @throws \Exception
 */
function isSameDate($expected, $actual, $format = 'Y-m-d', string $msg = ''): void
{
    $expectedObj = new \DateTime($expected);
    $actualObj = new \DateTime($actual);
    isSame('' . $expectedObj->format($format), '' . $actualObj->format($format), $msg);
}
