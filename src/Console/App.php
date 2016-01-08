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

namespace JBZoo\PHPUnit\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;

/**
 * Class App
 * @package JBZoo\PHPUnit\Console
 * @codeCoverageIgnore
 */
class App extends Application
{
    /**
     * @var array
     */
    private $_logo = array(
        '   _________  ______                  __ ______ _   _ ______ _   _       _ _    ',
        '  |_  | ___ \|___  /                 / / | ___ \ | | || ___ \ | | |     (_) |   ',
        '    | | |_/ /   / /  ___   ___      / /  | |_/ / |_| || |_/ / | | |_ __  _| |_  ',
        '    | | ___ \  / /  / _ \ / _ \    / /   |  __/|  _  ||  __/| | | | \'_ \| | __|',
        '/\__/ / |_/ /./ /__| (_) | (_) |  / /    | |   | | | || |   | |_| | | | | | |_  ',
        '\____/\____/ \_____/\___/ \___/  /_/     \_|   \_| |_/\_|    \___/|_| |_|_|\__| ',
        '                                                                   <comment>by SmetDenis</comment>',
    );

    /**
     * @param string $commandsDir The commands class directory
     * @throws \Exception
     */
    public function registerCommands($commandsDir)
    {
        if (!is_dir($commandsDir)) {
            throw new \Exception('First argument is not directory!');
        }

        $this->_registerCommands($commandsDir);
    }

    /**
     * Register commands
     * @param $commandsDir
     */
    protected function _registerCommands($commandsDir)
    {
        $finder = new Finder();
        $finder->files()->name('*.php')->in($commandsDir);

        $namespace = __NAMESPACE__;

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {

            include_once $file->getRealPath();

            $reflection = new \ReflectionClass($namespace . '\\Command\\' . $file->getBasename('.php'));

            if ($reflection->isSubclassOf('Symfony\\Component\\Console\\Command\\Command') &&
                !$reflection->isAbstract()
            ) {
                $this->add($reflection->newInstance());
            }
        }
    }

    /**
     * Returns the long version of the application.
     * @return string The long application version
     */
    public function getLongVersion()
    {
        $result = implode(PHP_EOL, $this->_logo);

        return '<info>' . $result . '</info>';
    }
}
