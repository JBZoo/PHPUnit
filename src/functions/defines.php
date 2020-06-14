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

// phpcs:ignoreFile

// Paths
$paths = [
    realpath('./../../../vendor/autoload.php'),
    realpath('./../../vendor/autoload.php'),
    realpath('./../vendor/autoload.php'),
    realpath('./vendor/autoload.php'),
    realpath('vendor/autoload.php')
];

foreach ($paths as $path) {
    if ($path && !defined('JBZOO_PHPUNIT')) {
        define('JBZOO_PHPUNIT', true);

        // System
        define('CRLF', "\r\n");
        define('LF', "\n");
        define('DS', DIRECTORY_SEPARATOR);

        define('PROJECT_ROOT', dirname($path, 2));
        define('PROJECT_BUILD', PROJECT_ROOT . DS . 'build');
        define('PROJECT_SRC', PROJECT_ROOT . DS . 'src');
        define('PROJECT_TESTS', PROJECT_ROOT . DS . 'tests');
        define('PROJECT_RES', PROJECT_ROOT . DS . 'resources');
        break;
    }
}
