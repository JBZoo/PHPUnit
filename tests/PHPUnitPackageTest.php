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

final class PHPUnitPackageTest extends \JBZoo\Codestyle\PHPUnit\AbstractPackageTest
{
    protected string $packageName = 'PHPUnit';

    protected static function stepBeforeTests(): ?array
    {
        return [
            'name' => 'Start web-server',
            'run'  => 'make server-start --no-print-directory',
        ];
    }

    protected static function stepAfterTests(): ?array
    {
        return [
            'name' => 'Stop web-server',
            'run'  => 'make server-stop --no-print-directory',
        ];
    }
}
