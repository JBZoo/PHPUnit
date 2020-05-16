<?php

/**
 * JBZoo PHPUnit
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    PHPUnit
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/PHPUnit
 * @author     Denis Smetannikov <denis@jbzoo.com>
 */

declare(strict_types=1);

namespace JBZoo\PHPUnit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\VarDumper;

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
            if (!class_exists(VarDumper::class)) {
                $isInit = false;
                return $isInit;
            }

            /** @psalm-suppress MissingClosureParamType */
            VarDumper::setHandler(function ($variable) {
                $maxStringWidth = 1024 * 16; // Show first 16kb only, optimization

                $varCloner = new VarCloner();
                $varCloner->setMaxItems(500);
                $varCloner->setMaxString($maxStringWidth);

                $cliDumper = new CliDumper(null, 'UTF-8', CliDumper::DUMP_COMMA_SEPARATOR);
                $cliDumper->setMaxStringWidth($maxStringWidth);
                $cliDumper->setIndentPad('    ');
                $cliDumper->setDisplayOptions(['fileLinkFormat' => false]);

                $varClone = $varCloner->cloneVar($variable);

                $cliDumper->dump($varClone);
            });

            $isInit = true;
        }

        return $isInit;
    }
}
