# JBZoo PHPUnit  [![Build Status](https://travis-ci.org/JBZoo/PHPUnit.svg?branch=master)](https://travis-ci.org/JBZoo/PHPUnit)      [![Coverage Status](https://coveralls.io/repos/github/JBZoo/PHPUnit/badge.svg?branch=master)](https://coveralls.io/github/JBZoo/PHPUnit?branch=master)

PHPUnit Toolbox for simple syntax and dev

[![License](https://poser.pugx.org/JBZoo/PHPUnit/license)](https://packagist.org/packages/JBZoo/PHPUnit)
[![Latest Stable Version](https://poser.pugx.org/JBZoo/PHPUnit/v/stable)](https://packagist.org/packages/JBZoo/PHPUnit) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/JBZoo/PHPUnit/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/JBZoo/PHPUnit/?branch=master)


#### Composer.json content
```js
{
    "require-dev": {
        "jbzoo/phpunit": "^2.1"
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
        isFileEq(__FILE__, __FILE__);
        is($this->openFile(__FILE__), $this->openFile(__FILE__));
        isFile(__FILE__);
        isDir(__DIR__);
    }

    public function testMemoryLeak()
    {
        startProfiler();

        $max    = 10000;
        $result = array();
        for ($i = 0; $i < $max; $i++) {
            $result[] = array($i);
        }

        loopProfiler($max, true);

        /*
           Report: TIME: 52.00 ms/0.00 ms;   MEMO: 8 324.95 KB/0.08 KB;   COUNT: 100 000
        */
    }

    public function testHtml()
    {
        $html = '<body>
            <div class="test-class">
                <p>qwerty</p>
            </div>
            <span class="empty-1"> </span>
            <span class="empty-2"></span>
        </body>';

        isHtmlContain($html, 'body > div.test-class p', 'qwerty');
        isHtmlNotContain($html, 'body > div.test-class p', 'qwerty-123');
    }

    public function testSkip()
    {
        skip('Some reason to skip this test');
    }

    public function testFail()
    {
        fail('Some reason to fail this test');
    }

    /**
     * @expectedException \JBZoo\__PACKAGE__\Exception
     */
    public function testShouldShowException()
    {
        throw new Exception('Test message');
    }

    public function testBenchmark()
    {
        // Compare performance of functions
        runBench(array(
            'md5'   => function () {
                $string = str_repeat(mt_rand(0, 9), 1024 * 1024);
                return md5($string);
            },
            'sha1'  => function () {
                $string = str_repeat(mt_rand(0, 9), 1024 * 1024);
                return sha1($string);
            },
            'crc32' => function () {
                $string = str_repeat(mt_rand(0, 9), 1024 * 1024);
                return crc32($string);
            },
        ), array('count' => 500, 'name' => 'Hash functions'));

        /* Result:

            ---------- Start benchmark: Hash functions  ----------
            Running tests 500 times
            PHP Overhead: time=58 ms; memory=0 B;

            Testing 1/3 : md5 ... Done!
            Testing 2/3 : sha1 ... Done!
            Testing 3/3 : crc32 ... Done!

            Name of test    Time, ms    Time, %     Memory    Memory, %
            crc32              1 551          ~    1.25 MB            ~
            md5                1 938         25    1.25 MB            ~
            sha1               2 776         79    1.25 MB            ~

            TOTAL TIME: 6 547.37 ms/4.36 ms;   MEMO: 41.05 KB/0.03 KB;   COUNT: 1 500
            ---------- Finish benchmark: Hash functions  ----------
        */
    }
}

```

### License

MIT
