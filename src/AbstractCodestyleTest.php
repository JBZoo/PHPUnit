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

use SplFileInfo;
use Symfony\Component\Finder\Finder;

/**
 * Class AbstractCodestyleTest
 *
 * @package JBZoo\PHPUnit
 */
abstract class AbstractCodestyleTest extends PHPUnit
{
    #### Configurations. Override it if you need for your project. #####################################################

    /**
     * @var string
     */
    protected $projectRoot = PROJECT_ROOT;

    /**
     * @var string[]
     */
    protected $excludePaths = [
        '.git',
        '.idea',
        'bin',
        'bower_components',
        'build',
        'fonts',
        'fixtures',
        'logs',
        'node_modules',
        'resources',
        'vendor',
        'temp',
        'tmp',
    ];

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (!$this->projectRoot) {
            throw new Exception('$this->projectRoot is undefined!');
        }

        if (!class_exists(Finder::class)) {
            throw new Exception('symfony/finder requreid for CodeStyle unit tests');
        }
    }

    #### Test cases ####################################################################################################

    /**
     * Test line endings
     */
    public function testFiles(): void
    {
        $finder = (new Finder())
            ->files()
            ->in($this->projectRoot)
            ->exclude($this->excludePaths)
            ->ignoreDotFiles(false)
            ->name('*.html')
            ->name('*.xml')
            ->name('*.js')
            ->name('*.jsx')
            ->name('*.css')
            ->name('*.less')
            ->name('*.php')
            ->name('*.phtml')
            ->name('*.ini')
            ->name('*.json')
            ->name('*.txt')
            ->name('*.md')
            ->name('*.yml')
            ->name('*.yaml')
            ->name('.*.yml')
            ->name('.*.yaml')
            ->notName('*.min.*')
            ->exclude('*.Makefile')
            ->exclude('Makefile');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            if ($content) {
                isTrue(
                    strpos($content, "\r") === false,
                    'The file contains prohibited symbol "\r" (CARRIAGE RETURN) : ' . $file->getPathname()
                );

                isTrue(
                    strpos($content, "\t") === false,
                    'The file contains prohibited symbol "\t" (TAB) : ' . $file->getPathname()
                );
            }
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Try to find cyrilic symbols in the code
     */
    public function testCyrillic(): void
    {
        $finder = (new Finder())
            ->files()
            ->in($this->projectRoot)
            ->exclude($this->excludePaths)
            ->notPath(basename(__FILE__))
            ->ignoreDotFiles(false)
            ->notName('/\.md$/')
            ->notName('/\.min\.(js|css)$/')
            ->notName('/\.min\.(js|css)\.map$/');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());

            /** @noinspection NotOptimalRegularExpressionsInspection */
            if (preg_match('#[А-Яа-яЁё]#ius', (string)$content)) {
                fail('File contains cyrilic symbols: ' . $file); // Short message in terminal
            }
        }

        isTrue(true); // One assert is a minimum for test complete
    }
}
