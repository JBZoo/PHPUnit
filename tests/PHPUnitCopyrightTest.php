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

/**
 * Class PHPUnitCopyrightTest
 *
 * @package JBZoo\PHPUnit
 */
class PHPUnitCopyrightTest extends AbstractCopyrightTest
{
    protected string $packageName     = 'PHPUnit';
    protected bool   $isPhpStrictType = true;

    /**
     * Ignore list for
     *
     * @var array
     */
    protected array $excludePaths = [
        '.git',
        '.idea',
        'build',
        'logs',
        'vendor',
    ];
}
