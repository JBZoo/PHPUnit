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

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Class Command
 * @package JBZoo\PHPUnit\Console
 */
class Command extends SymfonyCommand
{
    /**
     * @var OutputInterface
     */
    protected $_out;

    /**
     * @var InputInterface
     */
    protected $_in;

    /**
     * @param string $name The command name
     * @return Command The current instance
     * @throws \InvalidArgumentException When the name is invalid
     * @api
     */
    public function setName($name)
    {
        //$configPath   = PATH_ROOT . '/config/' . str_replace(':', '-', $name) . '.ini';
        //$this->config = new Ini($configPath);

        return parent::setName($name);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function _executePrepare(InputInterface $input, OutputInterface $output)
    {
        $this->_out = $output;
        $this->_in  = $input;
    }

    /**
     * @param string|array $messages The message as an array of lines of a single string
     */
    protected function _($messages)
    {
        $this->_out->writeln($messages);
    }

    /**
     * @return Filesystem
     */
    protected function _fs()
    {
        return new Filesystem();
    }

    /**
     * @return Finder
     */
    protected function _finder()
    {
        return new Finder();
    }

    /**
     * @param string $name
     * @param null   $default
     * @return mixed
     */
    protected function _getOpt($name, $default = null)
    {
        $value = $this->_in->getOption($name);
        return null === $value ? $default : $value;
    }
}
