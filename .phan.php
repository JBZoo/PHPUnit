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

$default = include __DIR__ . '/vendor/jbzoo/codestyle/src/phan.php';

return \array_merge($default, [
    'file_list' => [
        'src/functions/defines.php',
        'src/functions/aliases.php',
        'src/functions/tools.php',
    ],

    'directory_list' => [
        // project
        'bin',
        'src',

        // Libs
        'vendor/jbzoo/data',
        'vendor/jbzoo/http-client',
        'vendor/jbzoo/utils',
        'vendor/jbzoo/markdown',

        'vendor/phpunit/php-code-coverage/src',
        'vendor/phpunit/php-file-iterator/src',
        'vendor/phpunit/phpunit/src',
        'vendor/symfony/finder',
        'vendor/ulrichsg/getopt-php/src',
    ],
]);
