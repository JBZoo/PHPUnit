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

/**
 * Class PHPUnitAliasesTest
 *
 * @package JBZoo\PHPUnit
 */
class PHPUnitAliasesTest extends PHPUnit
{
    public function testBoolean()
    {
        isTrue(true);
        isFalse(false);

        isNull(null);
        isNotNull(false);
        isNotNull('');
        isNotNull(0);
        isNotNull(.0);
    }

    public function testEmpty()
    {
        isEmpty(0);
        isEmpty('');
        isEmpty(null);
        isEmpty('0');
        isEmpty(.0);
        isEmpty([]);
        isEmpty(false);

        isNotEmpty([0]);
        isNotEmpty(true);
        isNotEmpty('1');
        isNotEmpty(1);
    }

    public function testEquals()
    {
        is(1, true);
        is([1, 2, 3], [1, 2, 3]);
        is(['a' => 1, 'b' => 2], ['b' => 2, 'a' => 1]);
        isNot(1, 2);

        isSame([1, 2, 3], [1, 2, 3]);
        isNotSame([1, 2, 3], [3, 2, 1]);

        isKey('test', ['test' => true]);
        isNotKey('undefined', ['test' => true]);

        isAttr('test', (object)['test' => true]);
        isNotAttr('undefined', (object)['test' => true]);

        isBatch([
            [1, 1],
            [2, 2],
        ]);
    }

    public function testObjects()
    {
        isClass('stdClass', new \stdClass());
        isClass(\stdClass::class, new \stdClass());
        isClass(PHPUnit::class, $this);
        isClass(PHPUnit::class, $this);
    }

    public function testArray()
    {
        isCount(0, []);
        isCount(1, [1]);
        isCount(2, [1, 3]);
    }

    public function testString()
    {
        isLike('#t.st#i', 'TESTO');
        isNotLike('#teeest#i', 'TESTO');

        isContain('t', 'test');
        isContain('T', 'test', true);
        isNotContain('x', 'test');
        isNotContain('T', 'test');
        isNotContain('E', 'test');
        isNotContain('X', 'test', true);
    }

    public function testFilesystem()
    {
        isFileEq(__FILE__, __FILE__);
        is(openFile(__FILE__), openFile(__FILE__));

        isFile(__FILE__);
        isDir(__DIR__);

        isNotFile(__DIR__);
        isNotFile(__FILE__ . '.qwerty');

        isNotDir(__FILE__);
        isNotDir(__DIR__ . '/qwerty');
    }

    public function testSkip()
    {
        skip('Some reason to skip this test');
    }

    public function testOthers()
    {
        isEmail('e@mail.com');
        isCurrentDate('now');
        isNotEmail('email.com');
    }

    public function testIsAmount()
    {
        isAmount('100.99', 100.98);
        isAmount('100.99', '100.97');
        isNotAmount('100.99', '100');
        isNotAmount('100.99', 100);

        isAmountCur(['100.99', 'USD'], [100.98, 'USD']);
        isNotAmountCur(['100.99', 'USD'], [100, 'USD']);
    }

    public function testIsSameDate()
    {
        isSameDate('now', 'now');
    }

    public function testIsDiffBetweenDates()
    {
        isDiffBetweenDates('now', '- 5min');
    }

    public function testIsFileNotContains()
    {
        isFileNotContains('Some not expected text', PROJECT_ROOT . '/README.md');
        isFileNotContains('Some not expected text', PROJECT_ROOT . '/README.md', true);
        isFileNotContains('Some not expected text', PROJECT_ROOT . '/README.md', false);
    }

    public function testIsFileContains()
    {
        isFileContains('Some expected text', __FILE__);
        isFileContains('Some  expected text', __FILE__, true);
        isFileContains('Some   expected text', __FILE__, false);
    }
}
