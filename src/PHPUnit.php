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

use JBZoo\Utils\Env;
use PHPUnit\Framework\TestCase;

abstract class PHPUnit extends TestCase
{
    /**
     * @suppress PhanPluginPossiblyStaticProtectedMethod
     */
    protected function getSourcePath(): string
    {
        return PROJECT_ROOT . '/' . \str_replace('`pwd`/', '', Env::string('PATH_SRC', 'src'));
    }
}
