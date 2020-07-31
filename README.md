# JBZoo / PHPUnit

[![Build Status](https://travis-ci.org/JBZoo/PHPUnit.svg)](https://travis-ci.org/JBZoo/PHPUnit)    [![Coverage Status](https://coveralls.io/repos/JBZoo/PHPUnit/badge.svg)](https://coveralls.io/github/JBZoo/PHPUnit)    [![Psalm Coverage](https://shepherd.dev/github/JBZoo/PHPUnit/coverage.svg)](https://shepherd.dev/github/JBZoo/PHPUnit)    
[![Stable Version](https://poser.pugx.org/jbzoo/phpunit/version)](https://packagist.org/packages/jbzoo/phpunit)    [![Latest Unstable Version](https://poser.pugx.org/jbzoo/phpunit/v/unstable)](https://packagist.org/packages/jbzoo/phpunit)    [![Dependents](https://poser.pugx.org/jbzoo/phpunit/dependents)](https://packagist.org/packages/jbzoo/phpunit/dependents?order_by=downloads)    [![GitHub Issues](https://img.shields.io/github/issues/jbzoo/phpunit)](https://github.com/JBZoo/PHPUnit/issues)    [![Total Downloads](https://poser.pugx.org/jbzoo/phpunit/downloads)](https://packagist.org/packages/jbzoo/phpunit/stats)    [![GitHub License](https://img.shields.io/github/license/jbzoo/phpunit)](https://github.com/JBZoo/PHPUnit/blob/master/LICENSE)



## Installation

```sh
composer require jbzoo/phpunit --dev
```


## Usage

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
        isBatch(array(
            array(1, 1),
            array(2, 2),
        ));

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

### License

MIT
