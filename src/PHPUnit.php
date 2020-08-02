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

use JBZoo\Utils\Env;
use PHPUnit\Framework\TestCase;

/**
 * Class PHPUnit
 *
 * @package JBZoo\PHPUnit
 */
abstract class PHPUnit extends TestCase
{
    /**
     * @return string
     */
    protected static function getSourcePath(): string
    {
        return PROJECT_ROOT . '/' . str_replace('`pwd`/', '', Env::string('PATH_SRC', 'src'));
    }
}
