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

// phpcs:ignoreFile

// Paths
if (!\defined('JBZOO_PHPUNIT')) {
    \define('JBZOO_PHPUNIT', true);

    $paths = [
        \realpath('./../../../vendor/autoload.php'),
        \realpath('./../../vendor/autoload.php'),
        \realpath('./../vendor/autoload.php'),
        \realpath('./vendor/autoload.php'),
        \realpath('vendor/autoload.php'),
    ];

    foreach ($paths as $path) {
        if (\is_string($path) && \file_exists($path)) {
            if (!\defined('CRLF')) {
                \define('CRLF', "\r\n");
            }

            if (!\defined('LF')) {
                \define('LF', "\n");
            }

            if (!\defined('DS')) {
                \define('DS', \DIRECTORY_SEPARATOR);
            }

            if (!\defined('PROJECT_ROOT')) {
                /** @phan-suppress-next-line PhanUndeclaredGlobalVariable */
                \define('PROJECT_ROOT', \dirname($path, 2));
            }

            if (!\defined('PROJECT_BUILD')) {
                \define('PROJECT_BUILD', PROJECT_ROOT . DS . 'build');
            }

            if (!\defined('PROJECT_SRC')) {
                \define('PROJECT_SRC', PROJECT_ROOT . DS . 'src');
            }

            if (!\defined('PROJECT_TESTS')) {
                \define('PROJECT_TESTS', PROJECT_ROOT . DS . 'tests');
            }

            if (!\defined('PROJECT_RES')) {
                \define('PROJECT_RES', PROJECT_ROOT . DS . 'resources');
            }

            break;
        }
    }
}
