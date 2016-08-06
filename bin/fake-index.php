<?php
/**
 * JBZoo PHPUnit
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   PHPUnit
 * @license   MIT
 * @copyright Copyright (C) JBZoo.com,  All rights reserved.
 * @link      https://github.com/JBZoo/PHPUnit
 */

use JBZoo\PHPUnit\CovCatcher;
use JBZoo\Utils\Env;
use JBZoo\Utils\Filter;
use JBZoo\Utils\Sys;
use Ulrichsg\Getopt\Getopt;

// $_SERVER['SCRIPT_NAME'] = '/index.php'; // #FUCK!!! https://bugs.php.net/bug.php?id=61286

// To help the built-in PHP dev server, check if the request was actually for
// something which should probably be served as a static file
if (PHP_SAPI == 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
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

// Try to find and load composer
$vendorPaths = array(
    realpath(__DIR__ . '/vendor/autoload.php'),
    realpath(__DIR__ . '/../vendor/autoload.php'),
    realpath(__DIR__ . '/../../vendor/autoload.php'),
    realpath(__DIR__ . '/../../../vendor/autoload.php'),
    realpath(__DIR__ . '/../../../../vendor/autoload.php'),
    realpath(__DIR__ . '/../../../../../vendor/autoload.php'),
    realpath('./vendor/autoload.php'),
);

foreach ($vendorPaths as $vendorPath) {
    if ($vendorPath && file_exists($vendorPath)) {
        require_once $vendorPath;
        break;
    }
}

// Parse additional arguments
$cliOptions = new Getopt(array(
    array(null, 'index', Getopt::OPTIONAL_ARGUMENT),
    array(null, 'cov-src', Getopt::OPTIONAL_ARGUMENT),
    array(null, 'cov-xml', Getopt::OPTIONAL_ARGUMENT),
    array(null, 'cov-cov', Getopt::OPTIONAL_ARGUMENT),
    array(null, 'cov-html', Getopt::OPTIONAL_ARGUMENT),
));

$cliOptions->parse(getenv('PHPUNINT_ARGUMENTS'));

$realIndex = isset($realIndex) ? $realIndex : realpath($cliOptions->getOption('index'));

if (class_exists('\JBZoo\PHPUnit\CovCatcher') && !(Sys::isPhp7() && Env::hasXdebug())) {
    $hash = md5(implode('||', array(
        serialize($_REQUEST),
        serialize($_SERVER),
        PHP_VERSION
    )));

    $catcher = new CovCatcher($hash, array(
        'src'  => $cliOptions->getOption('cov-src'),
        'xml'  => Filter::bool($cliOptions->getOption('cov-xml')),
        'cov'  => Filter::bool($cliOptions->getOption('cov-cov')),
        'html' => Filter::bool($cliOptions->getOption('cov-html')),
    ));

    $result = $catcher->includeFile($realIndex);

} else {
    $result = require_once $realIndex;
}

return $result;
