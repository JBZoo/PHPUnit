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
 */

declare(strict_types=1);

// phpcs:ignoreFile

/**
 * @param float $a
 * @param float $b
 * @return mixed
 */
function summa($a, $b)
{
    return $a + $b;
}

echo 'Some text message';

return summa(2, 3);
