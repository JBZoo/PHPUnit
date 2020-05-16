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

declare(strict_types=1);

namespace JBZoo\PHPUnit;

use JBZoo\Utils\Cli;

/**
 * Class MessageCollector
 *
 * @package JBZoo\PHPUnit
 */
class MessageBuffer
{
    /**
     * @var string[]
     */
    protected $info = [];

    /**
     * @var string[]
     */
    protected $error = [];

    /**
     * @return MessageBuffer
     */
    public static function getInstance(): MessageBuffer
    {
        static $instance;

        if (null === $instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @param string $message
     */
    public function info($message): void
    {
        $this->info[] = $message;
    }

    /**
     * @param string $message
     */
    public function error($message): void
    {
        $this->error[] = $message;
    }

    /**
     * Show messages only on php script die!
     */
    public function __destruct()
    {
        foreach ($this->info as $message) {
            Cli::out($message, false);
        }

        foreach ($this->error as $message) {
            Cli::err($message, false);
        }
    }
}
