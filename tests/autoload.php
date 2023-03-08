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

// main autoload
if ($autoload = \dirname(__DIR__) . '/vendor/autoload.php') {
    require_once $autoload;
} else {
    echo 'execute "composer update"';
    exit(1);
}
