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
 * @author    Denis Smetannikov <denis@jbzoo.com>
 */


// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart

// main autoload
if ($autoload = realpath('./vendor/autoload.php')) {
    require_once $autoload;
} else {
    die('execute "composer update"');
}

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath('.'));
}

// @codeCoverageIgnoreEnd
