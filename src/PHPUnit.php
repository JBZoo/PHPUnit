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

use PHPUnit\Framework\TestCase;

/**
 * Class PHPUnit
 *
 * @package JBZoo\PHPUnit
 */
abstract class PHPUnit extends TestCase
{
    // Example to init var dumper
    //protected function setUp(): void
    //{
    //    parent::setUp();
    //    $this->initVarDumper();
    //}

    /**
     * @return bool
     */
    protected function initVarDumper()
    {
        static $isInit;

        if (null === $isInit) {
            $isInit = include __DIR__ . '/functions/var-dumper.php';
        }

        return $isInit;
    }
}
