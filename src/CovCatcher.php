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
 * @see        https://github.com/JBZoo/PHPUnit
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
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CovCatcher
{
    public const MODE_REQUIRE      = 'require';
    public const MODE_REQUIRE_ONCE = 'require_once';

    protected bool $isStarted = false;

    protected ?CodeCoverage $coverage;

    /** @var array<bool|string> */
    protected array $default = [
        'cov'        => true,
        'xml'        => false,
        'html'       => false,
        'src'        => './src',
        'build_xml'  => './build/coverage_xml',
        'build_cov'  => './build/coverage_cov',
        'build_html' => './build/coverage_html',
    ];

    protected Data $config;

    protected string $hash = '';

    /**
     * @param  array<bool|string> $options
     */
    public function __construct(string $testName = '', array $options = [])
    {
        if (!\class_exists(Data::class)) {
            throw new Exception('jbzoo/data is required for CovCatcher');
        }

        if (!\class_exists(Env::class)) {
            throw new Exception('jbzoo/utils is required for CovCatcher');
        }

        $this->initConfig($options);

        $postFixName = \str_replace('.', '', \uniqid('', true));
        $this->hash  = $testName !== '' ? "{$testName}__{$postFixName}" : $postFixName;

        $this->coverage = null;
        if (Sys::hasXdebug()) {
            $covFilter = new Filter();
            $covFilter->includeDirectory($this->config->getString('src'));

            $driver         = (new Selector())->forLineAndPathCoverage($covFilter);
            $this->coverage = new CodeCoverage($driver, $covFilter);
        }
    }

    /**
     * Save report.
     */
    public function __destruct()
    {
        $this->stop();
        $this->createReports();
    }

    public function includeFile(string $filename, string $mode = self::MODE_REQUIRE): mixed
    {
        $this->start();

        $realpath = (string)\realpath($filename);

        if ($realpath !== '' && \file_exists($realpath) && \is_file($realpath)) {
            if ($mode === self::MODE_REQUIRE) {
                /** @psalm-suppress UnresolvableInclude */
                $result = require $realpath;
            } elseif ($mode === self::MODE_REQUIRE_ONCE) {
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
     * Start coverage process.
     */
    protected function start(): void
    {
        if (!$this->isStarted) {
            $this->isStarted = true;
            $this->coverage?->start($this->hash, true);
        }
    }

    /**
     * Stop or pause coverage process.
     */
    protected function stop(): void
    {
        if ($this->isStarted) {
            $this->isStarted = false;
            $this->coverage?->stop();
        }
    }

    /**
     * Stop or pause coverage process.
     */
    protected function createReports(): void
    {
        $reportXmlDir = $this->config->getString('build_xml');
        $isXmlEnabled = $this->config->getBool('xml');
        if ($isXmlEnabled) {
            self::prepareDirectory($reportXmlDir);
            $report = new Clover();
            if ($this->coverage !== null) {
                $report->process($this->coverage, $reportXmlDir . '/' . $this->hash . '.xml');
            }
        }

        $reportCovDir = $this->config->getString('build_cov');
        $isCovEnabled = $this->config->getBool('cov');
        if ($isCovEnabled) {
            self::prepareDirectory($reportCovDir);
            $report = new PHP();
            if ($this->coverage !== null) {
                $report->process($this->coverage, $reportCovDir . '/' . $this->hash . '.cov');
            }
        }

        $reportHtmlDir = $this->config->getString('build_html');
        $isHtmlEnabled = $this->config->getBool('html');
        if ($isHtmlEnabled) {
            self::prepareDirectory($reportHtmlDir);
            $report = new Facade();
            if ($this->coverage !== null) {
                $report->process($this->coverage, $reportHtmlDir . '/' . $this->hash);
            }
        }
    }

    /**
     * Prepare and init config.
     * @param array<null|bool|string> $options
     */
    protected function initConfig(array $options): void
    {
        $options = \array_filter($options, static fn ($option) => $option !== null);

        $this->config = new Data(\array_merge($this->default, $options));
    }

    protected static function prepareDirectory(string $dirPath): void
    {
        /** @phan-suppress-next-line PhanPluginDuplicateIfCondition */
        if (!\is_dir($dirPath) && !\mkdir($dirPath, 0777, true) && !\is_dir($dirPath)) {
            throw new \RuntimeException(\sprintf('Directory "%s" was not created', $dirPath));
        }
    }
}
