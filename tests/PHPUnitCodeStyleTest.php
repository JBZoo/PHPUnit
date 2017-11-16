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

namespace JBZoo\PHPUnit;

/**
 * Class PHPUnitCodeStyleTest
 *
 * @package JBZoo\PHPUnit
 */
class PHPUnitCodeStyleTest extends Codestyle
{
    protected $_packageName   = 'PHPUnit';
    protected $_packageAuthor = 'Denis Smetannikov <denis@jbzoo.com>';

    /**
     * Ignore list for
     *
     * @var array
     */
    protected $_excludePaths = [
        '.git',
        '.idea',
        'bin',
        'build',
        'logs',
        'vendor',
    ];
}
