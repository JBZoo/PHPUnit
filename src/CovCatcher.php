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
 * @author     Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\PHPUnit;

use JBZoo\Data\Data;
use JBZoo\Utils\Env;
use JBZoo\Utils\Sys;
use SebastianBergmann\CodeCoverage\CodeCoverage;
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
    protected $isStarted = false;

    /**
     * @var CodeCoverage|null
     */
    protected $coverage;

    /**
     * @var array<bool|string>
     */
    protected $default = [
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
    protected $config;

    /**
     * @var string
     */
    protected $hash = '';

    /**
     * CovCatcher constructor.
     *
     * @param string             $testName
     * @param array<bool|string> $options
     * @throws Exception
     */
    public function __construct($testName = null, array $options = [])
    {
        if (!class_exists(Data::class)) {
            throw new Exception('jbzoo/data is required for CovCatcher');
        }

        if (!class_exists(Env::class)) {
            throw new Exception('jbzoo/utils is required for CovCatcher');
        }

        $this->initConfig($options);

        $postFixName = str_replace('.', '', uniqid('', true));
        $this->hash = $testName ? "{$testName}__{$postFixName}" : $postFixName;

        $this->coverage = null;
        if (Sys::hasXdebug()) {
            $covFilter = new Filter();

            /** @phpstan-ignore-next-line */
            if (method_exists($covFilter, 'addDirectoryToWhitelist')) {
                /** @phan-suppress-next-line PhanUndeclaredMethod */
                $covFilter->addDirectoryToWhitelist($this->config->get('src'));
            }

            /** @phpstan-ignore-next-line */
            if (method_exists($covFilter, 'includeDirectory')) {
                /** @phan-suppress-next-line PhanUndeclaredMethod */
                $covFilter->includeDirectory($this->config->get('src'));
            }

            $selectorClass = '\SebastianBergmann\CodeCoverage\Driver\Selector';
            if (class_exists($selectorClass)) {
                /* @phan-suppress-next-line PhanTypeExpectedObjectOrClassName, PhanUndeclaredClass */
                $driver = (new $selectorClass())->forLineAndPathCoverage($covFilter);
                $this->coverage = new CodeCoverage($driver, $covFilter);
            } else {
                $this->coverage = new CodeCoverage(null, $covFilter);
            }
        }
    }

    /**
     * @param string $filename
     * @param string $mode
     * @return mixed
     * @throws Exception
     */
    public function includeFile(string $filename, string $mode = self::MODE_REQUIRE)
    {
        $this->start();

        $realpath = realpath($filename);

        if ($realpath && file_exists($realpath)) {
            if (self::MODE_REQUIRE === $mode) {
                /** @noinspection PhpIncludeInspection */
                /** @psalm-suppress UnresolvableInclude */
                $result = require $realpath;
            } elseif (self::MODE_REQUIRE_ONCE === $mode) {
                /** @noinspection PhpIncludeInspection */
                /** @noinspection UsingInclusionOnceReturnValueInspection */
                /** @psalm-suppress UnresolvableInclude */
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
     * @throws \ReflectionException
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
            if ($this->coverage) {
                $this->coverage->start($this->hash, true);
            }
        }
    }

    /**
     * Stop or pause coverage process
     */
    protected function stop(): void
    {
        if ($this->isStarted) {
            $this->isStarted = false;
            if ($this->coverage) {
                $this->coverage->stop();
            }
        }
    }

    /**
     * Stop or pause coverage process
     */
    protected function createReports(): void
    {
        $reportXmlDir = $this->config->get('build_xml');
        $isXmlEnabled = $this->config->get('xml', false, 'bool');
        if ($isXmlEnabled) {
            self::prepareDirectory($reportXmlDir);
            $report = new Clover();
            if ($this->coverage) {
                $report->process($this->coverage, $reportXmlDir . '/' . $this->hash . '.xml');
            }
        }

        $reportCovDir = $this->config->get('build_cov');
        $isCovEnabled = $this->config->get('cov', false, 'bool');
        if ($isCovEnabled) {
            self::prepareDirectory($reportCovDir);
            $report = new PHP();
            if ($this->coverage) {
                $report->process($this->coverage, $reportCovDir . '/' . $this->hash . '.cov');
            }
        }

        $reportHtmlDir = $this->config->get('build_html');
        $isHtmlEnabled = $this->config->get('html', false, 'bool');
        if ($isHtmlEnabled) {
            self::prepareDirectory($reportHtmlDir);
            $report = new Facade();
            if ($this->coverage) {
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
