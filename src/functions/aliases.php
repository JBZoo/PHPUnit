<?php

/**
 * JBZoo Toolbox - PHPUnit
 *
 * This file is part of the JBZoo Toolbox project.
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
function isBatch(array $testList, string $message = ''): void
{
    foreach ($testList as $testItem) {
        Assert::assertEquals($testItem[0], $testItem[1], $message);
    }
}

/**
 * @param bool   $value
 * @param string $message
 * @phan-suppress PhanPluginCanUseParamType
 */
function isTrue($value, string $message = ''): void
{
    Assert::assertTrue($value ? true : false, $message);
}

/**
 * @param bool   $value
 * @param string $message
 * @phan-suppress PhanPluginCanUseParamType
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
 * @phan-suppress  PhanPluginCanUseParamType
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
function isCount(int $expected, $actual, string $message = ''): void
{
    Assert::assertCount($expected, $actual, $message);
}


/**
 * @param string $pattern
 * @param string $value
 * @param string $message
 */
function isLike(string $pattern, string $value, string $message = ''): void
{
    $methodName = 'assertRegExp';
    if (method_exists(Assert::class, 'assertMatchesRegularExpression')) {
        $methodName = 'assertMatchesRegularExpression';
    }

    /** @phan-suppress-next-line PhanUndeclaredStaticMethod */
    Assert::$methodName($pattern, $value, $message);
}

/**
 * @param string $pattern
 * @param string $value
 * @param string $message
 */
function isNotLike(string $pattern, string $value, string $message = ''): void
{
    $methodName = 'assertNotRegExp';
    if (method_exists(Assert::class, 'assertDoesNotMatchRegularExpression')) {
        $methodName = 'assertDoesNotMatchRegularExpression';
    }

    /** @phan-suppress-next-line PhanUndeclaredStaticMethod */
    Assert::$methodName($pattern, $value, $message);
}

/**
 * @param string $filepathExpecte
 * @param string $filepathActual
 * @param string $message
 *
 */
function isFileEq(string $filepathExpecte, string $filepathActual, string $message = ''): void
{
    Assert::assertFileEquals($filepathExpecte, $filepathActual, $message);
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
 * @param string|int   $key
 * @param array<mixed> $array
 * @param string       $message
 */
function isKey($key, array $array, string $message = ''): void
{
    Assert::assertArrayHasKey($key, $array, $message);
}

/**
 * @param string|int   $key
 * @param array<mixed> $array
 * @param string       $message
 */
function isNotKey($key, array $array, string $message = ''): void
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
function isAttr(string $attrName, $object, string $message = ''): void
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
function isNotAttr(string $attrName, $object, string $message = ''): void
{
    Assert::assertNotNull($object, 'object ' . get_class($object) . " is not empty. {$message}");
    Assert::assertObjectNotHasAttribute($attrName, $object, $message);
}

/**
 * @param string $directoryPath
 * @param string $message
 */
function isDir(string $directoryPath, string $message = ''): void
{
    Assert::assertFileExists($directoryPath, $message);
    Assert::assertDirectoryExists($directoryPath);
}

/**
 * @param string $notDirectoryPath
 * @param string $message
 */
function isNotDir(string $notDirectoryPath, string $message = ''): void
{
    if (is_dir($notDirectoryPath)) {
        fail("\"{$notDirectoryPath}\" is directory");
    } else {
        success($message);
    }
}

/**
 * @param string $filePath
 * @param string $message
 */
function isFile(string $filePath, string $message = ''): void
{
    Assert::assertFileExists($filePath, $message);
}

/**
 * @param string $notFilePath
 * @param string $message
 */
function isNotFile(string $notFilePath, string $message = ''): void
{
    if (!is_dir($notFilePath)) {
        $methodName = 'assertFileNotExists';
        if (method_exists(Assert::class, 'assertFileDoesNotExist')) {
            $methodName = 'assertFileDoesNotExist';
        }

        /** @phan-suppress-next-line PhanUndeclaredStaticMethod */
        Assert::$methodName($notFilePath, $message);
    } else {
        success($message);
    }
}

/**
 * @param string $expected
 * @param string $haystack
 * @param bool   $ignoreCase
 * @param string $message
 */
function isContain(string $expected, string $haystack, bool $ignoreCase = false, string $message = ''): void
{
    if ($ignoreCase) {
        Assert::assertStringContainsStringIgnoringCase($expected, $haystack, $message);
    } else {
        Assert::assertStringContainsString($expected, $haystack, $message);
    }
}

/**
 * @param string $expected
 * @param string $haystack
 * @param bool   $ignoreCase
 * @param string $message
 */
function isNotContain(string $expected, string $haystack, bool $ignoreCase = false, string $message = ''): void
{
    if ($ignoreCase) {
        Assert::assertStringNotContainsStringIgnoringCase($expected, $haystack, $message);
    } else {
        Assert::assertStringNotContainsString($expected, $haystack, $message);
    }
}

/**** Custom Asserts **************************************************************************************************/

/**
 * @param string $email
 * @param string $message
 */
function isEmail(string $email, string $message = ''): void
{
    isTrue((bool)filter_var($email, FILTER_VALIDATE_EMAIL), $message);
}

/**
 * @param string $notEmail
 * @param string $message
 */
function isNotEmail(string $notEmail, string $message = ''): void
{
    isFalse((bool)filter_var($notEmail, FILTER_VALIDATE_EMAIL), $message);
}

/**
 * @param string $date
 * @param int    $timeDiff
 * @param string $message
 * @throws \Exception
 */
function isCurrentDate(string $date, int $timeDiff = 300, string $message = ''): void
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
function isAmount($expected, $actual, string $message = '', float $allowableDiff = 0.03): void
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
function isNotAmount($expected, $actual, string $message = '', float $allowableDiff = 0.03): void
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
function isAmountCur(array $expected, array $actual, string $message = '', float $allowableDiff = 0.03): void
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
function isNotAmountCur(array $expected, array $actual, string $message = '', float $allowableDiff = 0.03): void
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
function isDiffBetweenDates(string $date1, string $date2, int $timeDiff = 300, string $message = ''): void
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
function isSameDate(string $expected, string $actual, string $format = 'Y-m-d', string $message = ''): void
{
    $expectedObj = new \DateTime($expected);
    $actualObj = new \DateTime($actual);
    isSame('' . $expectedObj->format($format), '' . $actualObj->format($format), $message);
}
