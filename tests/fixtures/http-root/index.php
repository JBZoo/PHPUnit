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
 * @see       https://github.com/JBZoo/PHPUnit
 */

declare(strict_types=1);

/**
 * Show message.
 * @param string $key
 */
function showTestMessage($key): ?string
{
    return $_REQUEST[$key] ?? '200ok!';
}

echo showTestMessage('test');
