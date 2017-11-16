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

use GetOpt\GetOpt;
use JBZoo\Data\Data;
use JBZoo\PHPUnit\CovCatcher;
use JBZoo\Utils\Sys;

// To help the built-in PHP dev server, check if the request was actually for
// something which should probably be served as a static file
if (PHP_SAPI === 'cli-server') {
    $url = parse_url($_SERVER['REQUEST_URI']);
    $path = realpath($_SERVER['DOCUMENT_ROOT'] . $url['path']);

    if (is_dir($path)) {
        $realIndex = $path . '/index.php';

    } elseif (is_file($path)) {
        if (pathinfo($path, PATHINFO_EXTENSION) !== 'php') {
            return false;
        }
        $realIndex = $path;
    }
}

// Try to find and load composer autoloader
$vendorPaths = [
    realpath(__DIR__ . '/vendor/autoload.php'),
    realpath(__DIR__ . '/../vendor/autoload.php'),
    realpath(__DIR__ . '/../../vendor/autoload.php'),
    realpath(__DIR__ . '/../../../vendor/autoload.php'),
    realpath(__DIR__ . '/../../../../vendor/autoload.php'),
    realpath(__DIR__ . '/../../../../../vendor/autoload.php'),
    realpath('./vendor/autoload.php'),
];

foreach ($vendorPaths as $vendorPath) {
    if ($vendorPath && file_exists($vendorPath)) {
        require_once $vendorPath;
        break;
    }
}

// Parse additional options
$cliOptions = new Getopt([
    [null, 'index', GetOpt::OPTIONAL_ARGUMENT],
    [null, 'cov-src', GetOpt::OPTIONAL_ARGUMENT],
    [null, 'cov-xml', GetOpt::OPTIONAL_ARGUMENT],
    [null, 'cov-cov', GetOpt::OPTIONAL_ARGUMENT],
    [null, 'cov-html', GetOpt::OPTIONAL_ARGUMENT],
]);

$cliOptions->parse(getenv('PHPUNINT_ARGUMENTS'));

$realIndex = $realIndex ?? realpath($cliOptions->getOption('index'));

if (class_exists(CovCatcher::class) && Sys::hasXdebug()) {
    $testname = (new Data($_REQUEST))->get('testname');

    $coverHash = md5(implode('||', [serialize($_REQUEST), serialize($_SERVER), PHP_VERSION]));
    $coverHash = $testname ? $testname . '-' . $coverHash : $testname;

    $covCatcher = new CovCatcher($coverHash, [
        'src'  => $cliOptions->getOption('cov-src'),
        'xml'  => $cliOptions->getOption('cov-xml'),
        'cov'  => $cliOptions->getOption('cov-cov'),
        'html' => $cliOptions->getOption('cov-html'),
    ]);

    $result = $covCatcher->includeFile($realIndex);

} else {
    $result = require_once $realIndex;
}

return $result;
