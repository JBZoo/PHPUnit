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

use JBZoo\Data\Data;
use JBZoo\Utils\Env;
use JBZoo\Utils\Sys;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Driver\Selector;
use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\CodeCoverage\Report\Clover;
use SebastianBergmann\CodeCoverage\Report\Html\Facade;
use SebastianBergmann\CodeCoverage\Report\PHP;

/**
 * Class CovCatcher
 * @package JBZoo\PHPUnit
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CovCatcher
{
    public const MODE_REQUIRE      = 'require';
    public const MODE_REQUIRE_ONCE = 'require_once';

    /**
     * @var bool
     */
    protected bool $isStarted = false;

    /**
     * @var CodeCoverage|null
     */
    protected ?CodeCoverage $coverage;

    /**
     * @var array<bool|string>
     */
    protected array $default = [
        'cov'        => true,
        'xml'        => false,
        'html'       => false,
        'src'        => './src',
        'build_xml'  => './build/coverage_xml',
        'build_cov'  => './build/coverage_cov',
        'build_html' => './build/coverage_html',
    ];

    /**
     * @var Data
     */
    protected Data $config;

    /**
     * @var string
     */
    protected string $hash = '';

    /**
     * CovCatcher constructor.
     *
     * @param string             $testName
     * @param array<bool|string> $options
     * @throws Exception
     */
    public function __construct(string $testName = '', array $options = [])
    {
        if (!class_exists(Data::class)) {
            throw new Exception('jbzoo/data is required for CovCatcher');
        }

        if (!class_exists(Env::class)) {
            throw new Exception('jbzoo/utils is required for CovCatcher');
        }

        $this->initConfig($options);

        $postFixName = str_replace('.', '', uniqid('', true));
        $this->hash = '' !== $testName ? "{$testName}__{$postFixName}" : $postFixName;

        $this->coverage = null;
        if (Sys::hasXdebug()) {
            $covFilter = new Filter();
            $covFilter->includeDirectory($this->config->getString('src'));

            $driver = (new Selector())->forLineAndPathCoverage($covFilter);
            $this->coverage = new CodeCoverage($driver, $covFilter);
        }
    }

    /**
     * @param string $filename
     * @param string $mode
     * @return mixed
     * @throws Exception
     */
    public function includeFile(string $filename, string $mode = self::MODE_REQUIRE): mixed
    {
        $this->start();

        $realpath = (string)realpath($filename);

        if ('' !== $realpath && file_exists($realpath) && is_file($realpath)) {
            if (self::MODE_REQUIRE === $mode) {
                /** @psalm-suppress UnresolvableInclude */
                $result = require $realpath;
            } elseif (self::MODE_REQUIRE_ONCE === $mode) {
                $result = require_once $realpath;
            } else {
                throw new Exception("Undefined mode to include file: \"{$filename}\"");
            }
        } else {
            throw new Exception("Included file not found: \"{$filename}\"");
        }

        $this->stop();

        return $result;
    }

    /**
     * Save report
     */
    public function __destruct()
    {
        $this->stop();
        $this->createReports();
    }

    /**
     * Start coverage process
     */
    protected function start(): void
    {
        if (!$this->isStarted) {
            $this->isStarted = true;
            $this->coverage?->start($this->hash, true);
        }
    }

    /**
     * Stop or pause coverage process
     */
    protected function stop(): void
    {
        if ($this->isStarted) {
            $this->isStarted = false;
            $this->coverage?->stop();
        }
    }

    /**
     * Stop or pause coverage process
     */
    protected function createReports(): void
    {
        $reportXmlDir = $this->config->getString('build_xml');
        $isXmlEnabled = $this->config->getBool('xml');
        if ($isXmlEnabled) {
            self::prepareDirectory($reportXmlDir);
            $report = new Clover();
            if (null !== $this->coverage) {
                $report->process($this->coverage, $reportXmlDir . '/' . $this->hash . '.xml');
            }
        }

        $reportCovDir = $this->config->getString('build_cov');
        $isCovEnabled = $this->config->getBool('cov');
        if ($isCovEnabled) {
            self::prepareDirectory($reportCovDir);
            $report = new PHP();
            if (null !== $this->coverage) {
                $report->process($this->coverage, $reportCovDir . '/' . $this->hash . '.cov');
            }
        }

        $reportHtmlDir = $this->config->getString('build_html');
        $isHtmlEnabled = $this->config->getBool('html');
        if ($isHtmlEnabled) {
            self::prepareDirectory($reportHtmlDir);
            $report = new Facade();
            if (null !== $this->coverage) {
                $report->process($this->coverage, $reportHtmlDir . '/' . $this->hash);
            }
        }
    }

    /**
     * @param string $dirPath
     */
    protected static function prepareDirectory(string $dirPath): void
    {
        /** @phan-suppress-next-line PhanPluginDuplicateIfCondition */
        if (!is_dir($dirPath) && !mkdir($dirPath, 0777, true) && !is_dir($dirPath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dirPath));
        }
    }

    /**
     * Prepare and init config
     * @param array<bool|string|null> $options
     */
    protected function initConfig(array $options): void
    {
        $options = array_filter($options, static function ($option) {
            return null !== $option;
        });

        $this->config = new Data(array_merge($this->default, $options));
    }
}
