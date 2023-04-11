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

class PHPUnitAliasesTest extends PHPUnit
{
    public function testBoolean(): void
    {
        isTrue(true);
        isFalse(false);

        isNull(null);
        isNotNull(false);
        isNotNull('');
        isNotNull(0);
        isNotNull(.0);
    }

    public function testEmpty(): void
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

    public function testEquals(): void
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
    }

    public function testObjects(): void
    {
        isClass('stdClass', new \stdClass());
        isClass(\stdClass::class, new \stdClass());
        isClass(PHPUnit::class, $this);
        isClass(PHPUnit::class, $this);
    }

    public function testArray(): void
    {
        isCount(0, []);
        isCount(1, [1]);
        isCount(2, [1, 3]);
    }

    public function testString(): void
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

    public function testFilesystem(): void
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

    public function testSkip(): void
    {
        skip('Some reason to skip this test');
    }

    public function testOthers(): void
    {
        isEmail('e@mail.com');
        isCurrentDate('now');
        isNotEmail('email.com');
    }

    public function testIsAmount(): void
    {
        isAmount('100.99', 100.98);
        isAmount('100.99', '100.97');
        isAmount(['100.99', 'USD'], [100.98, 'USD']);

        isNotAmount('100.99', '100');
        isNotAmount('100.99', 100);
        isNotAmount(['100.99', 'USD'], [100, 'USD']);

        isAmountCur(['100.99', 'USD'], [100.98, 'USD']);
        isNotAmountCur(['100.99', 'USD'], [100, 'USD']);
    }

    public function testIsSameDate(): void
    {
        isSameDate('now', 'now');
    }

    public function testIsDiffBetweenDates(): void
    {
        isDiffBetweenDates('now', '-5min');
        isDiffBetweenDates('+5min', 'now');
        isDiffBetweenDates('+1min', 'now', 60);
        isDiffBetweenDates('-1min', 'now', 60);
        isDiffBetweenDates('now', '+1min', 60);
        isDiffBetweenDates('now', '-1min', 60);

        $isFail = false;

        try {
            isDiffBetweenDates('+5min', '-5min', 400, 'My message.');
        } catch (\Exception $exception) {
            isContain(
                'The expected difference between "+5min" and "-5min" is 400 seconds. ' .
                'The actual value is 600 seconds. My message.',
                $exception->getMessage(),
            );

            $isFail = true;
        }

        isTrue($isFail);
    }

    public function testIsDiffBetweenDatesLessThan(): void
    {
        isDiffBetweenDatesLessThan('now', '-4min');
        isDiffBetweenDatesLessThan('+4min', 'now');
        isDiffBetweenDatesLessThan('+59 sec', 'now', 60);
        isDiffBetweenDatesLessThan('-59 sec', 'now', 60);
        isDiffBetweenDatesLessThan('now', '+59 sec', 60);
        isDiffBetweenDatesLessThan('now', '-59 sec', 60);

        $isFail = false;

        try {
            isDiffBetweenDatesLessThan('+5min', '-5min', 100, 'My message.');
        } catch (\Exception $exception) {
            isContain(
                'Diff between dates: "+5min" and "-5min" is more than expected 100 seconds. ' .
                'The actual value is 600 seconds. My message.',
                $exception->getMessage(),
            );
            $isFail = true;
        }

        isTrue($isFail);
    }

    public function testIsDiffBetweenDatesMoreThan(): void
    {
        isDiffBetweenDatesMoreThan('now', '-6min');
        isDiffBetweenDatesMoreThan('+6min', 'now');
        isDiffBetweenDatesMoreThan('+59 sec', 'now', 50);
        isDiffBetweenDatesMoreThan('-59 sec', 'now', 50);
        isDiffBetweenDatesMoreThan('now', '+59 sec', 50);
        isDiffBetweenDatesMoreThan('now', '-59 sec', 50);

        $isFail = false;

        try {
            isDiffBetweenDatesMoreThan('+5min', '-5min', 1000, 'My message.');
        } catch (\Exception $exception) {
            isContain(
                'Diff between dates: "+5min" and "-5min" is less than expected 1000 seconds. ' .
                'The actual value is 600 seconds. My message.',
                $exception->getMessage(),
            );
            $isFail = true;
        }

        isTrue($isFail);
    }

    public function testIsFileNotContains(): void
    {
        isFileNotContains('Some not expected text', PROJECT_ROOT . '/README.md');
        isFileNotContains('Some not expected text', PROJECT_ROOT . '/README.md', true);
        isFileNotContains('Some not expected text', PROJECT_ROOT . '/README.md', false);
    }

    public function testIsFileContains(): void
    {
        isFileContains('Some expected text', __FILE__);
        isFileContains('Some  expected text', __FILE__, true);
        isFileContains('Some   expected text', __FILE__, false);
    }
}
