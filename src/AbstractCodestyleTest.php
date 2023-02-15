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

namespace JBZoo\PHPUnit;

use JBZoo\Utils\Cli;
use JBZoo\Utils\Env;
use Symfony\Component\Finder\Finder;

/**
 * Class AbstractCodestyleTest
 *
 * @package JBZoo\PHPUnit
 */
abstract class AbstractCodestyleTest extends PHPUnit
{
    #### Configurations. Override it if you need for your project. #####################################################

    protected string $projectRoot = PROJECT_ROOT;

    /**
     * @var string[]
     */
    protected array $excludePaths = [
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

        if ($this->projectRoot === "") {
            throw new Exception('$this->projectRoot is undefined!');
        }

        if (!class_exists(Finder::class)) {
            throw new Exception('symfony/finder is required for CodeStyle unit tests');
        }
    }

    #### Test cases ####################################################################################################

    /**
     * @param string $makeTargetName
     */
    protected function runToolViaMakefile(string $makeTargetName): void
    {
        // Test works only in PhpStorm ot TeamCity env. Please, use `make codestyle` for any other environments.
        if (isPhpStorm()) {
            $phpBin = Env::string('PHP_BIN', 'php');
            $cliCommand = implode(' ', [
                'TC_REPORT="tc-tests"',
                'TC_REPORT_MND="tc-tests"',
                'TEAMCITY_VERSION="2020.1.2"',
                "PHP_BIN=\"{$phpBin}\"",
                "make {$makeTargetName}",
            ]);

            //$start = microtime(true);

            // redirect error to std output
            try {
                $output = trim(Cli::exec($cliCommand, [], $this->projectRoot));
                Cli::out($output);
            } catch (\Exception $exception) {
                $output = trim($exception->getMessage());
                Cli::out($output);
            }

            //$time = microtime(true) - $start;
            //$seconds = (int)floor($time);
            //$mSeconds = str_replace('0.', '', (string)(round($time, 3) - (float)$seconds));
            //$finish = gmdate('i:s', $seconds);
            //Cli::out("{$makeTargetName}:\t{$finish}.{$mSeconds}");
        }

        success();
    }

    public function testCodeSniffer(): void
    {
        $this->runToolViaMakefile('test-phpcs-teamcity');
    }

    public function testMessDetector(): void
    {
        $this->runToolViaMakefile('test-phpmd-teamcity');
    }

    public function testMagicNumbers(): void
    {
        $this->runToolViaMakefile('test-phpmnd-teamcity');
    }

    public function testPhpStan(): void
    {
        $this->runToolViaMakefile('test-phpstan-teamcity');
    }

    public function testPsalm(): void
    {
        $this->runToolViaMakefile('test-psalm-teamcity');
    }

    public function testPhan(): void
    {
        $this->runToolViaMakefile('test-phan-teamcity');
    }

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

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = (string)openFile($file->getPathname());
            if ('' !== $content) {
                isTrue(
                    !str_contains($content, "\r"),
                    'The file contains prohibited symbol "\r" (CARRIAGE RETURN) : ' . $file->getPathname()
                );

                isTrue(
                    !str_contains($content, "\t"),
                    'The file contains prohibited symbol "\t" (TAB) : ' . $file->getPathname()
                );
            }
        }

        isTrue(true); // One assert is a minimum for test complete
    }

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

        foreach ($finder as $file) {
            $content = openFile($file->getPathname());

            /** @noinspection NotOptimalRegularExpressionsInspection */
            if (preg_match('#[А-Яа-яЁё]#ius', (string)$content) > 0) {
                fail('File contains cyrillic symbols: ' . $file); // Short message in terminal
            }
        }

        isTrue(true); // One assert is a minimum for test complete
    }
}
