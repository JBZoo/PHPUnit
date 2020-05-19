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

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\VarDumper;

if (!class_exists(VarDumper::class)) {
    return false;
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

return true;
