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

declare(strict_types=1);

namespace JBZoo\PHPUnit;

use DateTime;
use PHPUnit\Framework\Assert;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\Crawler;

/**** Controls ********************************************************************************************************/

/**
 * Skip current test
 *
 * @param string $message
 */
function skip(string $message): void
{
    Assert::markTestSkipped($message);
}

/**
 * Incomplete current test
 *
 * @param string $message
 */
function incomplete(string $message): void
{
    Assert::markTestIncomplete($message);
}

/**
 * Fail current test
 *
 * @param string $message
 */
function fail(string $message = ''): void
{
    Assert::fail($message);
}

/**
 * Success current test
 *
 * @param string $message
 */
function success(string $message = ''): void
{
    isTrue(true, $message);
}

/**** Asserts aliases *************************************************************************************************/

/**
 * @param mixed  $expected
 * @param mixed  $actual
 * @param string $message
 *
 * @SuppressWarnings(PHPMD.ShortMethodName)
 */
function is($expected, $actual, string $message = ''): void
{
    Assert::assertEquals($expected, $actual, $message);
}

/**
 * @param mixed  $expected
 * @param mixed  $actual
 * @param string $message
 */
function isNot($expected, $actual, string $message = ''): void
{
    Assert::assertNotEquals($expected, $actual, $message);
}

/**
 * @param array<mixed> $testList
 * @param string       $message
 * @deprecated
 */
function isBatch($testList, string $message = ''): void
{
    foreach ($testList as $testItem) {
        Assert::assertEquals($testItem[0], $testItem[1], $message);
    }
}

/**
 * @param bool   $value
 * @param string $message
 */
function isTrue($value, string $message = ''): void
{
    Assert::assertTrue($value ? true : false, $message);
}

/**
 * @param bool   $value
 * @param string $message
 */
function isFalse($value, string $message = ''): void
{
    Assert::assertFalse($value ? true : false, $message);
}


/**
 * @param string $expected
 * @param mixed  $className
 * @param string $message
 *
 * @psalm-template ExpectedType of object
 * @psalm-param    class-string<ExpectedType> $expected
 * @psalm-assert   ExpectedType $actual
 */
function isClass($expected, $className, string $message = ''): void
{
    Assert::assertInstanceOf($expected, $className, $message);
}

/**
 * @param int    $expected
 * @param mixed  $actual
 * @param string $message
 */
function isCount($expected, $actual, string $message = ''): void
{
    Assert::assertCount($expected, $actual, $message);
}


/**
 * @param string $pattern
 * @param string $value
 * @param string $message
 */
function isLike($pattern, $value, string $message = ''): void
{
    Assert::assertRegExp($pattern, $value, $message);
}

/**
 * @param string $pattern
 * @param string $value
 * @param string $message
 */
function isNotLike($pattern, $value, string $message = ''): void
{
    Assert::assertNotRegExp($pattern, $value, $message);
}

/**
 * @param string $filePathOrig
 * @param string $filePathCopy
 * @param string $message
 *
 */
function isFileEq($filePathOrig, $filePathCopy, string $message = ''): void
{
    Assert::assertFileEquals($filePathOrig, $filePathCopy, $message);
}

/**
 * @param mixed  $expected
 * @param mixed  $actual
 * @param string $message
 */
function isSame($expected, $actual, string $message = ''): void
{
    Assert::assertSame($expected, $actual, $message);
}

/**
 * @param mixed  $expected
 * @param mixed  $actual
 * @param string $message
 */
function isNotSame($expected, $actual, string $message = ''): void
{
    Assert::assertNotSame($expected, $actual, $message);
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
 * @param string $message
 */
function isEmpty($expected, string $message = ''): void
{
    Assert::assertEmpty($expected, $message);
}

/**
 * @param mixed  $expected
 * @param string $message
 */
function isNotEmpty($expected, string $message = ''): void
{
    Assert::assertNotEmpty($expected, $message);
}

/**
 * @param string       $key
 * @param array<mixed> $array
 * @param string       $message
 */
function isKey($key, $array, string $message = ''): void
{
    Assert::assertArrayHasKey($key, $array, $message);
}

/**
 * @param string       $key
 * @param array<mixed> $array
 * @param string       $message
 */
function isNotKey($key, $array, string $message = ''): void
{
    Assert::assertArrayNotHasKey($key, $array, $message);
}

/**
 * Assert object has an attribute
 *
 * @param string $attrName
 * @param mixed  $object
 * @param string $message
 */
function isAttr($attrName, $object, string $message = ''): void
{
    Assert::assertNotNull($object, 'object ' . get_class($object) . " is not empty. {$message}");
    Assert::assertObjectHasAttribute($attrName, $object, $message);
}

/**
 * Assert object has an attribute
 *
 * @param string $attrName
 * @param mixed  $object
 * @param string $message
 */
function isNotAttr($attrName, $object, string $message = ''): void
{
    Assert::assertNotNull($object, 'object ' . get_class($object) . " is not empty. {$message}");
    Assert::assertObjectNotHasAttribute($attrName, $object, $message);
}

/**
 * @param string $path
 * @param string $message
 */
function isDir($path, string $message = ''): void
{
    Assert::assertFileExists($path, $message);
    Assert::assertDirectoryExists($path);
}

/**
 * @param string $path
 * @param string $message
 */
function isNotDir($path, string $message = ''): void
{
    if (is_dir($path)) {
        fail("\"{$path}\" is directory");
    } else {
        success($message);
    }
}

/**
 * @param string $path
 * @param string $message
 */
function isFile($path, string $message = ''): void
{
    Assert::assertFileExists($path, $message);
}

/**
 * @param string $path
 * @param string $message
 */
function isNotFile($path, string $message = ''): void
{
    if (!is_dir($path)) {
        Assert::assertFileNotExists($path, $message);
    } else {
        success($message);
    }
}

/**
 * @param string $expected
 * @param string $value
 * @param bool   $ignoreCase
 * @param string $message
 */
function isContain($expected, $value, $ignoreCase = false, string $message = ''): void
{
    if ($ignoreCase) {
        Assert::assertStringContainsStringIgnoringCase($expected, $value, $message);
    } else {
        Assert::assertStringContainsString($expected, $value, $message);
    }
}

/**
 * @param string $expected
 * @param string $value
 * @param bool   $ignoreCase
 * @param string $message
 */
function isNotContain($expected, $value, $ignoreCase = false, string $message = ''): void
{
    if ($ignoreCase) {
        Assert::assertStringNotContainsStringIgnoringCase($expected, $value, $message);
    } else {
        Assert::assertStringNotContainsString($expected, $value, $message);
    }
}

/**** Custom Asserts **************************************************************************************************/

/**
 * Is CSS selector find in the HTML code
 *
 * @param string $html
 * @param string $selector
 * @param mixed  $expected
 * @param string $message
 * @throws Exception
 */
function isHtmlContain($html, $selector, $expected = null, string $message = ''): void
{
    if (!class_exists(Crawler::class)) {
        throw new Exception('symfony/dom-crawler is required for isHtmlContain() function');
    }

    if (!class_exists(CssSelectorConverter::class)) {
        throw new Exception('symfony/css-selector is required for isHtmlContain() function');
    }

    $findText = null;

    try {
        $crawler = new Crawler($html);
        $findText = $crawler->filter($selector)->text();
        isSame((string)$expected, $findText);
    } catch (\Exception $exception) {
        if (!$expected) {
            success($message);
        } else {
            $message = $message ? $message . ' // ' : '';
            fail($message . 'Crawler: ' . $exception->getMessage());
        }
    }
}

/**
 * Is NOT find CSS-selector find in the HTML code
 *
 * @param string $html
 * @param string $selector
 * @param mixed  $expected
 * @param string $message
 * @throws Exception
 */
function isHtmlNotContain($html, $selector, $expected, string $message = ''): void
{
    if (!class_exists(Crawler::class)) {
        throw new Exception('symfony/dom-crawler is required for isHtmlNotContain() function');
    }

    if (!class_exists(CssSelectorConverter::class)) {
        throw new Exception('symfony/css-selector is required for isHtmlNotContain() function');
    }

    $findText = null;

    try {
        $crawler = new Crawler($html);
        $findText = $crawler->filter($selector)->text();
        isNotSame((string)$expected, $findText);
    } catch (\Exception $exception) {
        if (!$findText) {
            success($message);
        } else {
            $message = $message ? $message . ' // ' : '';
            fail($message . 'Crawler: ' . $exception->getMessage());
        }
    }
}

/**
 * @param string $mixedVar
 * @param string $message
 */
function isEmail($mixedVar, string $message = ''): void
{
    isTrue((bool)filter_var($mixedVar, FILTER_VALIDATE_EMAIL), $message);
}

/**
 * @param string $mixedVar
 * @param string $message
 */
function isNotEmail($mixedVar, string $message = ''): void
{
    isFalse((bool)filter_var($mixedVar, FILTER_VALIDATE_EMAIL), $message);
}

/**
 * @param string $date
 * @param int    $timeDiff
 * @param string $message
 * @throws \Exception
 */
function isCurrentDate($date, $timeDiff = 300, string $message = ''): void
{
    $nowDate = new DateTime('now');
    $checkDate = new DateTime($date);
    Assert::assertEqualsWithDelta($nowDate->getTimestamp(), $checkDate->getTimestamp(), $timeDiff, $message);
}

/**
 * @param float|string|int $expected
 * @param float|string|int $actual
 * @param string           $message
 * @param float            $allowableDiff
 */
function isAmount($expected, $actual, string $message = '', $allowableDiff = 0.03): void
{
    $message = $message ?: 'Diff: ' . ((float)$expected - (float)$actual) . "; Expected diff={$allowableDiff}";
    Assert::assertEqualsWithDelta((float)$expected, (float)$actual, $allowableDiff, $message);
}

/**
 * @param float|string|int $expected
 * @param float|string|int $actual
 * @param string           $message
 * @param float            $allowableDiff
 */
function isNotAmount($expected, $actual, string $message = '', $allowableDiff = 0.03): void
{
    $message = $message ?: 'Diff: ' . ((float)$expected - (float)$actual) . "; Expected diff={$allowableDiff}";
    Assert::assertNotEqualsWithDelta((float)$expected, (float)$actual, $allowableDiff, $message);
}

/**
 * @param array<float|string|int> $expected
 * @param array<float|string|int> $actual
 * @param string                  $message
 * @param float                   $allowableDiff
 */
function isAmountCur(array $expected, array $actual, string $message = '', $allowableDiff = 0.03): void
{
    $message = $message ?: 'Actual diff=' . ((float)$expected[0] - (float)$actual[0])
        . "; Expected diff={$allowableDiff}";

    Assert::assertEqualsWithDelta((float)$expected[0], (float)$actual[0], $allowableDiff, $message);
    isSame($expected[1], $actual[1], $message);
}

/**
 * @param array<float|string|int> $expected
 * @param array<float|string|int> $actual
 * @param string                  $message
 * @param float                   $allowableDiff
 */
function isNotAmountCur(array $expected, array $actual, string $message = '', $allowableDiff = 0.03): void
{
    $message = $message ?: 'Actual diff=' . ((float)$expected[0] - (float)$actual[0])
        . "; Expected diff={$allowableDiff}";

    Assert::assertNotEqualsWithDelta((float)$expected[0], (float)$actual[0], $allowableDiff, $message);
    isSame($expected[1], $actual[1], $message);
}

/**
 * @param string $date1
 * @param string $date2
 * @param int    $timeDiff
 * @param string $message
 * @throws \Exception
 */
function isDiffBetweenDates($date1, $date2, $timeDiff = 300, string $message = ''): void
{
    $dateObj1 = new \DateTime($date1);
    $dateObj2 = new \DateTime($date2);
    isTrue(
        abs((int)$dateObj1->getTimestamp() - (int)$dateObj2->getTimestamp()) === $timeDiff,
        "Diff between dates: {$date1} and {$date2} is more then {$timeDiff} seconds. {$message}"
    );
}

/**
 * @param string $expected
 * @param string $actual
 * @param string $format
 * @param string $message
 * @throws \Exception
 */
function isSameDate($expected, $actual, $format = 'Y-m-d', string $message = ''): void
{
    $expectedObj = new \DateTime($expected);
    $actualObj = new \DateTime($actual);
    isSame('' . $expectedObj->format($format), '' . $actualObj->format($format), $message);
}
