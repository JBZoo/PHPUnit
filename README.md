# JBZoo / PHPUnit

[![CI](https://github.com/JBZoo/PHPUnit/actions/workflows/main.yml/badge.svg?branch=master)](https://github.com/JBZoo/PHPUnit/actions/workflows/main.yml?query=branch%3Amaster)
[![Coverage Status](https://coveralls.io/repos/github/JBZoo/PHPUnit/badge.svg?branch=master)](https://coveralls.io/github/JBZoo/PHPUnit?branch=master)
[![Psalm Coverage](https://shepherd.dev/github/JBZoo/PHPUnit/coverage.svg)](https://shepherd.dev/github/JBZoo/PHPUnit)
[![Psalm Level](https://shepherd.dev/github/JBZoo/PHPUnit/level.svg)](https://shepherd.dev/github/JBZoo/PHPUnit)
[![CodeFactor](https://www.codefactor.io/repository/github/jbzoo/phpunit/badge)](https://www.codefactor.io/repository/github/jbzoo/phpunit/issues)

[![Stable Version](https://poser.pugx.org/jbzoo/phpunit/version)](https://packagist.org/packages/jbzoo/phpunit/)
[![Total Downloads](https://poser.pugx.org/jbzoo/phpunit/downloads)](https://packagist.org/packages/jbzoo/phpunit/stats)
[![Dependents](https://poser.pugx.org/jbzoo/phpunit/dependents)](https://packagist.org/packages/jbzoo/phpunit/dependents?order_by=downloads)
[![GitHub License](https://img.shields.io/github/license/jbzoo/phpunit)](https://github.com/JBZoo/PHPUnit/blob/master/LICENSE)

PHPUnit toolbox with short assertion aliases and useful testing utilities. This library provides a more concise and readable way to write tests by offering shorter function names for common PHPUnit assertions.

## Features

- **Short assertion aliases** - Use `isTrue()` instead of `$this->assertTrue()`
- **Extended assertions** - Additional assertions for emails, dates, amounts, file contents
- **Environment detection** - Detect if running under TeamCity, Travis, PhpStorm
- **Built-in utilities** - Tools for test organization and debugging
- **PHP 8.3+ support** - Modern PHP features and strict typing

## Installation

```sh
composer require jbzoo/phpunit --dev
```

## Quick Start

```php
namespace JBZoo\PHPUnit;

/**
 * Class PackageTest
 * @package JBZoo\PHPUnit
 */
class PackageTest extends PHPUnit
{
    public function testSimple()
    {
        // Boolean
        isTrue(true);
        isFalse(false);

        // null
        isNull(null);

        // Check is variable empty
        isEmpty(0);
        isEmpty('');
        isEmpty(null);
        isEmpty('0');
        isEmpty(.0);
        isEmpty(array());

        // Equals
        is(1, true);
        is(array(1, 2, 3), array(1, 2, 3));
        isSame(array(1, 2, 3), array(1, 2, 3));

        // Array, Object etc
        isKey('test', array('test' => true));
        isNotKey('undef-kest', array('test' => true));

        isAttr('test', (object)array('test' => true));
        isNotAttr('undef-test', (object)array('test' => true));

        // Instance Of ...
        isClass(JBZoo\PHPUnit\PHPUnit::class, $this);

        // Count props
        isCount(0, array());
        isCount(1, array(1));
        isCount(2, array(1, 3));

        // regExp
        isLike('#t.st#i', 'TESTO');
        isNotLike('#teeest#i', 'TESTO');

        // Strings
        isContain('t', 'test');
        isNotContain('x', 'test');

        // Filesystem
        isFileEq(__FILE__, __FILE__);
        isFile(__FILE__);
        isDir(__DIR__);
    }

    public function testSkip()
    {
        skip('Some reason to skip this test');
    }

    public function testFail()
    {
        fail('Some reason to fail this test');
    }
}
```

## Available Assertions

### Basic Assertions
- `is($expected, $actual)` - assertEquals
- `isNot($expected, $actual)` - assertNotEquals
- `isSame($expected, $actual)` - assertSame
- `isNotSame($expected, $actual)` - assertNotSame
- `isTrue($value)` - assertTrue
- `isFalse($value)` - assertFalse
- `isNull($value)` - assertNull
- `isNotNull($value)` - assertNotNull
- `isEmpty($value)` - assertEmpty
- `isNotEmpty($value)` - assertNotEmpty

### Arrays & Objects
- `isKey($key, $array)` - assertArrayHasKey
- `isNotKey($key, $array)` - assertArrayNotHasKey
- `isAttr($name, $object)` - Check object attribute exists
- `isNotAttr($name, $object)` - Check object attribute doesn't exist
- `isClass($expected, $actual)` - assertInstanceOf
- `isCount($expected, $countable)` - assertCount

### Strings & RegExp
- `isLike($pattern, $value)` - assertMatchesRegularExpression
- `isNotLike($pattern, $value)` - assertDoesNotMatchRegularExpression
- `isContain($needle, $haystack)` - String contains check
- `isNotContain($needle, $haystack)` - String doesn't contain check

### Files & Filesystem
- `isFile($path)` - assertFileExists
- `isNotFile($path)` - File doesn't exist
- `isDir($path)` - Directory exists
- `isNotDir($path)` - Directory doesn't exist
- `isFileEq($expected, $actual)` - assertFileEquals
- `isFileContains($expected, $filepath)` - File contains string
- `isFileNotContains($expected, $filepath)` - File doesn't contain string

### Extended Assertions
- `isEmail($email)` - Valid email check
- `isNotEmail($email)` - Invalid email check
- `isCurrentDate($date, $timeDiff)` - Date is close to current time
- `isSameDate($expected, $actual, $format)` - Date comparison
- `isAmount($expected, $actual, $allowableDiff)` - Amount comparison with tolerance
- `isDiffBetweenDates($date1, $date2, $expectedDiff)` - Time difference check

### Test Control
- `skip($message)` - markTestSkipped
- `fail($message)` - fail test
- `incomplete($message)` - markTestIncomplete
- `success($message)` - Mark test as successful

### Environment Detection
- `isWin()` - Running on Windows
- `isTeamCity()` - Running under TeamCity
- `isTravis()` - Running under Travis CI
- `isPhpStorm()` - Running in PhpStorm

## Requirements

- PHP 8.3 or higher
- PHPUnit ^9.6.29
- ext-filter, ext-mbstring

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License

MIT
