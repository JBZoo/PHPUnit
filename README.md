# JBZoo / PHPUnit

[![CI](https://github.com/JBZoo/PHPUnit/actions/workflows/main.yml/badge.svg?branch=master)](https://github.com/JBZoo/PHPUnit/actions/workflows/main.yml?query=branch%3Amaster)    [![codecov](https://codecov.io/gh/JBZoo/PHPUnit/branch/master/graph/badge.svg)](https://codecov.io/gh/JBZoo/PHPUnit/branch/master)    [![Psalm Coverage](https://shepherd.dev/github/JBZoo/PHPUnit/coverage.svg)](https://shepherd.dev/github/JBZoo/PHPUnit)    [![CodeFactor](https://www.codefactor.io/repository/github/jbzoo/phpunit/badge)](https://www.codefactor.io/repository/github/jbzoo/phpunit/issues)    [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jbzoo/phpunit/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jbzoo/phpunit/?branch=master)    [![PHP Strict Types](https://img.shields.io/badge/strict__types-%3D1-brightgreen)](https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.strict)    
[![Stable Version](https://poser.pugx.org/jbzoo/phpunit/version)](https://packagist.org/packages/jbzoo/phpunit)    [![Total Downloads](https://poser.pugx.org/jbzoo/phpunit/downloads)](https://packagist.org/packages/jbzoo/phpunit/stats)    [![Dependents](https://poser.pugx.org/jbzoo/phpunit/dependents)](https://packagist.org/packages/jbzoo/phpunit/dependents?order_by=downloads)    [![Visitors](https://visitor-badge.glitch.me/badge?page_id=jbzoo.phpunit)]()    [![GitHub License](https://img.shields.io/github/license/jbzoo/phpunit)](https://github.com/JBZoo/PHPUnit/blob/master/LICENSE)


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
