<?php

/**
 * JBZoo Toolbox - PHPUnit.
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @see        https://github.com/JBZoo/PHPUnit
 */

declare(strict_types=1);

namespace JBZoo\PHPUnit;

use PHPUnit\Framework\Assert;

// Controls

/**
 * Skip current test.
 */
function skip(string $message): void
{
    Assert::markTestSkipped($message);
}

/**
 * Incomplete current test.
 */
function incomplete(string $message): void
{
    Assert::markTestIncomplete($message);
}

/**
 * Fail current test.
 */
function fail(string $message = ''): void
{
    Assert::fail($message);
}

/**
 * Success current test.
 */
function success(string $message = ''): void
{
    isTrue(true, $message);
}

// Asserts aliases

/**
 * @param mixed $expected
 * @param mixed $actual
 *
 * @SuppressWarnings(PHPMD.ShortMethodName)
 */
function is($expected, $actual, string $message = ''): void
{
    Assert::assertEquals($expected, $actual, $message);
}

/**
 * @param mixed $expected
 * @param mixed $actual
 */
function isNot($expected, $actual, string $message = ''): void
{
    Assert::assertNotEquals($expected, $actual, $message);
}

function isTrue(mixed $value, string $message = ''): void
{
    Assert::assertTrue($value, $message);
}

function isFalse(mixed $value, string $message = ''): void
{
    Assert::assertFalse($value, $message);
}

/**
 * @param string $expected
 * @param mixed  $className
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
 * @param mixed $actual
 */
function isCount(int $expected, $actual, string $message = ''): void
{
    Assert::assertCount($expected, $actual, $message);
}

function isLike(string $pattern, string $value, string $message = ''): void
{
    Assert::assertMatchesRegularExpression($pattern, $value, $message);
}

function isNotLike(string $pattern, string $value, string $message = ''): void
{
    Assert::assertDoesNotMatchRegularExpression($pattern, $value, $message);
}

function isFileEq(string $filepathExpecte, string $filepathActual, string $message = ''): void
{
    Assert::assertFileEquals($filepathExpecte, $filepathActual, $message);
}

/**
 * @param mixed $expected
 * @param mixed $actual
 */
function isSame($expected, $actual, string $message = ''): void
{
    Assert::assertSame($expected, $actual, $message);
}

/**
 * @param mixed $expected
 * @param mixed $actual
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
 * @param mixed $expected
 */
function isEmpty($expected, string $message = ''): void
{
    Assert::assertEmpty($expected, $message);
}

/**
 * @param mixed $expected
 */
function isNotEmpty($expected, string $message = ''): void
{
    Assert::assertNotEmpty($expected, $message);
}

/**
 * @param int|string $key
 */
function isKey($key, array $array, string $message = ''): void
{
    Assert::assertArrayHasKey($key, $array, $message);
}

/**
 * @param int|string $key
 */
function isNotKey($key, array $array, string $message = ''): void
{
    Assert::assertArrayNotHasKey($key, $array, $message);
}

/**
 * Assert object has an attribute.
 *
 * @param mixed $object
 */
function isAttr(string $attrName, $object, string $message = ''): void
{
    Assert::assertNotNull($object, 'object ' . $object::class . " is not empty. {$message}");
    isTrue(\property_exists($object, $attrName));
}

/**
 * Assert object has an attribute.
 *
 * @param mixed $object
 */
function isNotAttr(string $attrName, $object, string $message = ''): void
{
    Assert::assertNotNull($object, 'object ' . $object::class . " is not empty. {$message}");
    isFalse(\property_exists($object, $attrName));
}

function isDir(string $directoryPath, string $message = ''): void
{
    Assert::assertFileExists($directoryPath, $message);
    Assert::assertDirectoryExists($directoryPath);
}

function isNotDir(string $notDirectoryPath, string $message = ''): void
{
    if (\is_dir($notDirectoryPath)) {
        fail("\"{$notDirectoryPath}\" is directory");
    } else {
        success($message);
    }
}

function isFile(string $filePath, string $message = ''): void
{
    Assert::assertFileExists($filePath, $message);
}

function isNotFile(string $notFilePath, string $message = ''): void
{
    if (!\is_dir($notFilePath)) {
        Assert::assertFileDoesNotExist($notFilePath, $message);
    } else {
        success($message);
    }
}

function isContain(string $expected, string $haystack, bool $ignoreCase = false, string $message = ''): void
{
    if ($ignoreCase) {
        Assert::assertStringContainsStringIgnoringCase($expected, $haystack, $message);
    } else {
        Assert::assertStringContainsString($expected, $haystack, $message);
    }
}

function isNotContain(string $expected, string $haystack, bool $ignoreCase = false, string $message = ''): void
{
    if ($ignoreCase) {
        Assert::assertStringNotContainsStringIgnoringCase($expected, $haystack, $message);
    } else {
        Assert::assertStringNotContainsString($expected, $haystack, $message);
    }
}

// Custom Asserts

function isEmail(string $email, string $message = ''): void
{
    isTrue((bool)\filter_var($email, \FILTER_VALIDATE_EMAIL), $message);
}

function isNotEmail(string $notEmail, string $message = ''): void
{
    isFalse((bool)\filter_var($notEmail, \FILTER_VALIDATE_EMAIL), $message);
}

function isCurrentDate(string $date, int $timeDiff = 300, string $message = ''): void
{
    $nowDate   = new \DateTime('now');
    $checkDate = new \DateTime($date);
    Assert::assertEqualsWithDelta($nowDate->getTimestamp(), $checkDate->getTimestamp(), $timeDiff, $message);
}

function isAmount(
    array|float|int|string $expected,
    array|float|int|string $actual,
    string $message = '',
    float $allowableDiff = 0.03,
): void {
    if (!\is_array($expected) && !\is_array($actual)) {
        Assert::assertEqualsWithDelta(
            (float)$expected,
            (float)$actual,
            $allowableDiff,
            'Diff: ' . ((float)$expected - (float)$actual) . "; Expected diff={$allowableDiff}; " . $message,
        );
    } elseif (\is_array($expected) && \is_array($actual)) {
        isAmountCur($expected, $actual, $message, $allowableDiff);
    } else {
        fail('$expected and $actual both must be "array" OR "float|string|int"');
    }
}

function isNotAmount(
    array|float|int|string $expected,
    array|float|int|string $actual,
    string $message = '',
    float $allowableDiff = 0.03,
): void {
    if (!\is_array($expected) && !\is_array($actual)) {
        Assert::assertNotEqualsWithDelta(
            (float)$expected,
            (float)$actual,
            $allowableDiff,
            'Diff: ' . ((float)$expected - (float)$actual) . "; Expected diff={$allowableDiff}; " . $message,
        );
    } elseif (\is_array($expected) && \is_array($actual)) {
        isNotAmountCur($expected, $actual, $message, $allowableDiff);
    } else {
        fail('$expected and $actual both must be "array" OR "float|string|int"');
    }
}

/**
 * @param array<float|int|string> $expected
 * @param array<float|int|string> $actual
 */
function isAmountCur(array $expected, array $actual, string $message = '', float $allowableDiff = 0.03): void
{
    $numberOfArgs = 2;

    isTrue(\count($expected) === $numberOfArgs, $message);
    isTrue(\count($actual) === $numberOfArgs, $message);

    $diff = (float)$expected[0] - (float)$actual[0];
    $message .= " Actual diff={$diff}; Expected diff={$allowableDiff}";

    isTrue(\is_string($expected[1]), $message);
    isTrue(\is_string($actual[1]), $message);
    isSame($expected[1], $actual[1], $message);
    isNotEmpty($expected[1], $message);
    isNotEmpty($actual[1], $message);

    Assert::assertEqualsWithDelta((float)$expected[0], (float)$actual[0], $allowableDiff, $message);
}

/**
 * @param array<float|int|string> $expected
 * @param array<float|int|string> $actual
 */
function isNotAmountCur(array $expected, array $actual, string $message = '', float $allowableDiff = 0.03): void
{
    $numberOfArgs = 2;

    isTrue(\count($expected) === $numberOfArgs, $message);
    isTrue(\count($actual) === $numberOfArgs, $message);

    $diff = (float)$expected[0] - (float)$actual[0];
    $message .= " Actual diff={$diff}; Expected diff={$allowableDiff}";

    isTrue(\is_string($expected[1]), $message);
    isTrue(\is_string($actual[1]), $message);
    isSame($expected[1], $actual[1], $message);
    isNotEmpty($expected[1], $message);
    isNotEmpty($actual[1], $message);

    Assert::assertNotEqualsWithDelta((float)$expected[0], (float)$actual[0], $allowableDiff, $message);
}

function isDiffBetweenDates(string $date1, string $date2, float $expectedDiff = 300.0, string $message = ''): void
{
    $dateObj1   = new \DateTime($date1);
    $dateObj2   = new \DateTime($date2);
    $actualDiff = \abs((float)($dateObj1->getTimestamp() - $dateObj2->getTimestamp()));

    isTrue(
        $actualDiff === $expectedDiff,
        \trim(
            "The expected difference between \"{$date1}\" and \"{$date2}\" is {$expectedDiff} seconds. " .
            "The actual value is {$actualDiff} seconds. {$message}",
        ),
    );
}

function isDiffBetweenDatesLessThan(
    string $date1,
    string $date2,
    int $expectedMaxDiff = 300,
    string $message = '',
): void {
    $dateObj1   = new \DateTime($date1);
    $dateObj2   = new \DateTime($date2);
    $actualDiff = \abs((float)($dateObj1->getTimestamp() - $dateObj2->getTimestamp()));
    isTrue(
        $actualDiff < $expectedMaxDiff,
        \trim(
            "Diff between dates: \"{$date1}\" and \"{$date2}\" is more than expected {$expectedMaxDiff} seconds. " .
            "The actual value is {$actualDiff} seconds. {$message}",
        ),
    );
}

function isDiffBetweenDatesMoreThan(
    string $date1,
    string $date2,
    int $expectedMinDiff = 300,
    string $message = '',
): void {
    $dateObj1   = new \DateTime($date1);
    $dateObj2   = new \DateTime($date2);
    $actualDiff = \abs((float)($dateObj1->getTimestamp() - $dateObj2->getTimestamp()));
    isTrue(
        $actualDiff > $expectedMinDiff,
        \trim(
            "Diff between dates: \"{$date1}\" and \"{$date2}\" is less than expected {$expectedMinDiff} seconds. " .
            "The actual value is {$actualDiff} seconds. {$message}",
        ),
    );
}

function isSameDate(string $expected, string $actual, string $format = 'Y-m-d', string $message = ''): void
{
    $expectedObj = new \DateTime($expected);
    $actualObj   = new \DateTime($actual);
    isSame($expectedObj->format($format), $actualObj->format($format), $message);
}

function isFileContains(string $expected, string $filepath, bool $ignoreCase = false, string $message = ''): void
{
    isFile($filepath);

    $errMessage = \implode("\n", [
        "The file doesn't contain expected text. " . $message,
        "See: {$filepath}",
        'Expected text:',
        \str_repeat('-', 80),
        $expected,
        \str_repeat('-', 80),
    ]);

    $fileContent = (string)\file_get_contents($filepath);

    if ($ignoreCase) {
        isTrue(\mb_stripos($fileContent, $expected) !== false, $errMessage);
    } else {
        isTrue(\str_contains($fileContent, $expected), $errMessage);
    }
}

function isFileNotContains(string $expected, string $filepath, bool $ignoreCase = false, string $message = ''): void
{
    isFile($filepath);

    $errMessage = \implode("\n", [
        "The file shouldn't contain expected text. " . $message,
        "See: {$filepath}",
        'Expected text:',
        \str_repeat('-', 80),
        $expected,
        \str_repeat('-', 80),
    ]);

    $fileContent = (string)\file_get_contents($filepath);

    if ($ignoreCase) {
        isTrue(\mb_stripos($fileContent, $expected) === false, $errMessage);
    } else {
        isTrue(!\str_contains($fileContent, $expected), $errMessage);
    }
}
