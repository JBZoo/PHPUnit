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
 */

declare(strict_types=1);

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
 * @param array  $testList
 * @param string $message
 * @deprecated
 */
function isBatch(array $testList, string $message = ''): void
{
    foreach ($testList as $testItem) {
        Assert::assertEquals($testItem[0], $testItem[1], $message);
    }
}

/**
 * @param mixed  $value
 * @param string $message
 * @phan-suppress PhanPluginCanUseParamType
 */
function isTrue($value, string $message = ''): void
{
    Assert::assertTrue($value, $message);
}

/**
 * @param mixed  $value
 * @param string $message
 * @phan-suppress PhanPluginCanUseParamType
 */
function isFalse($value, string $message = ''): void
{
    Assert::assertFalse($value, $message);
}


/**
 * @param string                              $expected
 * @param mixed                               $className
 * @param string                              $message
 *
 * @psalm-template ExpectedType of object
 * @psalm-param    class-string<ExpectedType> $expected
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
    Assert::assertMatchesRegularExpression($pattern, $value, $message);
}

/**
 * @param string $pattern
 * @param string $value
 * @param string $message
 */
function isNotLike(string $pattern, string $value, string $message = ''): void
{
    Assert::assertDoesNotMatchRegularExpression($pattern, $value, $message);
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
 * @param string|int $key
 * @param array      $array
 * @param string     $message
 */
function isKey($key, array $array, string $message = ''): void
{
    Assert::assertArrayHasKey($key, $array, $message);
}

/**
 * @param string|int $key
 * @param array      $array
 * @param string     $message
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
    isTrue(property_exists($object, $attrName));
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
    isFalse(property_exists($object, $attrName));
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
        Assert::assertFileDoesNotExist($notFilePath, $message);
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
 * @param array|float|string|int $expected
 * @param array|float|string|int $actual
 * @param string                 $message
 * @param float                  $allowableDiff
 */
function isAmount($expected, $actual, string $message = '', float $allowableDiff = 0.03): void
{
    if (!is_array($expected) && !is_array($actual)) {
        $message = $message ?: 'Diff: ' . ((float)$expected - (float)$actual) . "; Expected diff={$allowableDiff}";
        Assert::assertEqualsWithDelta((float)$expected, (float)$actual, $allowableDiff, $message);
    } elseif (is_array($expected) && is_array($actual)) {
        isAmountCur($expected, $actual, $message, $allowableDiff);
    } else {
        fail('$expected and $actual both must be "array" OR "float|string|int"');
    }
}

/**
 * @param array|float|string|int $expected
 * @param array|float|string|int $actual
 * @param string                 $message
 * @param float                  $allowableDiff
 */
function isNotAmount($expected, $actual, string $message = '', float $allowableDiff = 0.03): void
{
    if (!is_array($expected) && !is_array($actual)) {
        $message = $message ?: 'Diff: ' . ((float)$expected - (float)$actual) . "; Expected diff={$allowableDiff}";
        Assert::assertNotEqualsWithDelta((float)$expected, (float)$actual, $allowableDiff, $message);
    } elseif (is_array($expected) && is_array($actual)) {
        isNotAmountCur($expected, $actual, $message, $allowableDiff);
    } else {
        fail('$expected and $actual both must be "array" OR "float|string|int"');
    }
}

/**
 * @param array<float|string|int> $expected
 * @param array<float|string|int> $actual
 * @param string                  $message
 * @param float                   $allowableDiff
 */
function isAmountCur(array $expected, array $actual, string $message = '', float $allowableDiff = 0.03): void
{
    $numberOfArgs = 2;

    isTrue(count($expected) === $numberOfArgs, $message);
    isTrue(count($actual) === $numberOfArgs, $message);

    $diff = (float)$expected[0] - (float)$actual[0];
    $message .= " Actual diff={$diff}; Expected diff={$allowableDiff}";

    isTrue(is_string($expected[1]), $message);
    isTrue(is_string($actual[1]), $message);
    isSame($expected[1], $actual[1], $message);
    isNotEmpty($expected[1], $message);
    isNotEmpty($actual[1], $message);

    Assert::assertEqualsWithDelta((float)$expected[0], (float)$actual[0], $allowableDiff, $message);
}

/**
 * @param array<float|string|int> $expected
 * @param array<float|string|int> $actual
 * @param string                  $message
 * @param float                   $allowableDiff
 */
function isNotAmountCur(array $expected, array $actual, string $message = '', float $allowableDiff = 0.03): void
{
    $numberOfArgs = 2;

    isTrue(count($expected) === $numberOfArgs, $message);
    isTrue(count($actual) === $numberOfArgs, $message);

    $diff = (float)$expected[0] - (float)$actual[0];
    $message .= " Actual diff={$diff}; Expected diff={$allowableDiff}";

    isTrue(is_string($expected[1]), $message);
    isTrue(is_string($actual[1]), $message);
    isSame($expected[1], $actual[1], $message);
    isNotEmpty($expected[1], $message);
    isNotEmpty($actual[1], $message);

    Assert::assertNotEqualsWithDelta((float)$expected[0], (float)$actual[0], $allowableDiff, $message);
}

/**
 * @param string $date1
 * @param string $date2
 * @param float  $expectedDiff
 * @param string $message
 * @throws \Exception
 */
function isDiffBetweenDates(string $date1, string $date2, float $expectedDiff = 300.0, string $message = ''): void
{
    $dateObj1 = new \DateTime($date1);
    $dateObj2 = new \DateTime($date2);
    $actualDiff = abs((float)($dateObj1->getTimestamp() - $dateObj2->getTimestamp()));

    isTrue(
        $actualDiff === $expectedDiff,
        trim(
            "The expected difference between \"{$date1}\" and \"{$date2}\" is {$expectedDiff} seconds. " .
            "The actual value is {$actualDiff} seconds. {$message}"
        )
    );
}

/**
 * @param string $date1
 * @param string $date2
 * @param int    $expectedMaxDiff
 * @param string $message
 * @throws \Exception
 */
function isDiffBetweenDatesLessThan(
    string $date1,
    string $date2,
    int $expectedMaxDiff = 300,
    string $message = ''
): void {
    $dateObj1 = new \DateTime($date1);
    $dateObj2 = new \DateTime($date2);
    $actualDiff = abs((float)($dateObj1->getTimestamp() - $dateObj2->getTimestamp()));
    isTrue(
        $actualDiff < $expectedMaxDiff,
        trim(
            "Diff between dates: \"{$date1}\" and \"{$date2}\" is more than expected {$expectedMaxDiff} seconds. " .
            "The actual value is {$actualDiff} seconds. {$message}"
        )
    );
}

/**
 * @param string $date1
 * @param string $date2
 * @param int    $expectedMinDiff
 * @param string $message
 * @throws \Exception
 */
function isDiffBetweenDatesMoreThan(
    string $date1,
    string $date2,
    int $expectedMinDiff = 300,
    string $message = ''
): void {
    $dateObj1 = new \DateTime($date1);
    $dateObj2 = new \DateTime($date2);
    $actualDiff = abs((float)($dateObj1->getTimestamp() - $dateObj2->getTimestamp()));
    isTrue(
        $actualDiff > $expectedMinDiff,
        trim(
            "Diff between dates: \"{$date1}\" and \"{$date2}\" is less than expected {$expectedMinDiff} seconds. " .
            "The actual value is {$actualDiff} seconds. {$message}"
        )
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

/**
 * @param string $expected
 * @param string $filepath
 * @param bool   $ignoreCase
 * @param string $message
 */
function isFileContains(string $expected, string $filepath, bool $ignoreCase = false, string $message = ''): void
{
    isFile($filepath);

    $errMessage = implode("\n", [
        "The file doesn't contain expected text. " . $message,
        "See: {$filepath}",
        "Expected text:",
        str_repeat('-', 80),
        $expected,
        str_repeat('-', 80),
    ]);

    $fileContent = (string)file_get_contents($filepath);

    if ($ignoreCase) {
        isTrue(mb_stripos($fileContent, $expected) !== false, $errMessage);
    } else {
        isTrue(mb_strpos($fileContent, $expected) !== false, $errMessage);
    }
}

/**
 * @param string $expected
 * @param string $filepath
 * @param bool   $ignoreCase
 * @param string $message
 */
function isFileNotContains(string $expected, string $filepath, bool $ignoreCase = false, string $message = ''): void
{
    isFile($filepath);

    $errMessage = implode("\n", [
        "The file shouldn't contain expected text. " . ($message ?: ''),
        "See: {$filepath}",
        "Expected text:",
        str_repeat('-', 80),
        $expected,
        str_repeat('-', 80),
    ]);

    $fileContent = (string)file_get_contents($filepath);

    if ($ignoreCase) {
        isTrue(mb_stripos($fileContent, $expected) === false, $errMessage);
    } else {
        isTrue(mb_strpos($fileContent, $expected) === false, $errMessage);
    }
}
