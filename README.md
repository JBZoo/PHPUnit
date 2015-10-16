# JBZoo PHPUnit  [![Build Status](https://travis-ci.org/JBZoo/PHPUnit.svg?branch=master)](https://travis-ci.org/JBZoo/PHPUnit)      [![Coverage Status](https://coveralls.io/repos/JBZoo/PHPUnit/badge.svg?branch=master&service=github)](https://coveralls.io/github/JBZoo/PHPUnit?branch=master)

PHPUnit Toolbox for simple syntax and dev

[![License](https://poser.pugx.org/JBZoo/PHPUnit/license)](https://packagist.org/packages/JBZoo/PHPUnit)
[![Latest Stable Version](https://poser.pugx.org/JBZoo/PHPUnit/v/stable)](https://packagist.org/packages/JBZoo/PHPUnit)


#### Composer.json content
```js
{
    "require": {
        "php": ">=5.3.10"
    },
    "require-dev": {
        "jbzoo/phpunit": "^1.0"
    }
}
```


#### Test class example
```php
<?php
/**
 * JBZoo __PACKAGE__
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   __PACKAGE__
 * @license   MIT
 * @copyright Copyright (C) JBZoo.com,  All rights reserved.
 * @link      https://github.com/JBZoo/__PACKAGE__
 */

namespace JBZoo\PHPUnit;

use JBZoo\__PACKAGE__\__PACKAGE__;
use JBZoo\__PACKAGE__\Exception;

/**
 * Class Test
 * @package JBZoo\PHPUnit
 */
class Test extends PHPUnit
{

    public function test()
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
        same(array(1, 2, 3), array(1, 2, 3));
        isBatch(array(
            array(1, 1),
            array(2, 2),
        ));

        // Array, Object etc
        isKey('test', array('test' => true));
        isAttr('test', (object)array('test' => true));

        // Instance Of ...
        isClass('stdClass', new \stdClass());
        isClass('\stdClass', new \stdClass());
        isClass('\JBZoo\PHPUnit\PHPUnit', $this);
        isClass('JBZoo\PHPUnit\PHPUnit', $this);

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

        // Misc
        isCount(2, $this->getFileList(__DIR__));
        isCount(1, $this->getFileList(__DIR__, 'aliases'));
        isCount(0, $this->getFileList(__DIR__, '\.qwerty$'));
        isCount(1, $this->getFileList(__DIR__ . '/..', '\.travis'));

        // Filesystem
        fileEq(__FILE__, __FILE__);
        is($this->openFile(__FILE__), $this->openFile(__FILE__));
        isFile(__FILE__);
        isDir(__DIR__);

        // profiles (memory leaks...)
        $this->startProfiler();

        $max    = 10000;
        $result = array();
        for ($i = 0; $i < $max; $i++) {
            $result[] = array($i);
        }

        $this->loopProfiler($max, true);
        $this->loopProfiler($max, false);
    }
    
    public function testSkip()
    {
        skip('Some reason to skip this test');
    }

    /**
     * @expectedException \JBZoo\__PACKAGE__\Exception
     */
    public function testShouldShowException()
    {
        throw new Exception('Test message');
    }
}

```

### License

The MIT License (MIT)
Copyright (c) 2015 JBZoo Content Construction Kit (CCK)
