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

use JBZoo\Data\Data;
use JBZoo\Utils\Env;
use JBZoo\Utils\Str;
use JBZoo\Utils\Sys;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\CodeCoverage\Report\Clover;
use SebastianBergmann\CodeCoverage\Report\Html\Facade;
use SebastianBergmann\CodeCoverage\Report\PHP;

/**
 * Class CovCatcher
 *
 * @package JBZoo\PHPUnit
 */
class CovCatcher
{
    public const MODE_REQUIRE      = 'require';
    public const MODE_REQUIRE_ONCE = 'require_once';

    /**
     * @var bool
     */
    protected $isStart = false;

    /**
     * @var CodeCoverage|null
     */
    protected $coverage;

    /**
     * @var array
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
     * @var Data|null
     */
    protected $config;

    /**
     * @var string
     */
    protected $hash = '';

    /**
     * CovCatcher constructor.
     *
     * @param string $testName
     * @param array  $options
     * @throws Exception
     */
    public function __construct($testName = null, array $options = [])
    {
        if (!class_exists(Data::class)) {
            throw new Exception('jbzoo/data required for CovCatcher');
        }

        if (!class_exists(Env::class)) {
            throw new Exception('jbzoo/utils required for CovCatcher');
        }

        $this->initConfig($options);

        $this->hash = $testName . '__' . str_replace('.', '', uniqid('', true));

        if (Sys::hasXdebug()) {
            $covFilter = new Filter();
            $covFilter->addDirectoryToWhitelist($this->config->get('src'));
            $this->coverage = new CodeCoverage(null, $covFilter);
        }
    }

    /**
     * @param string $filename
     * @param string $mode
     * @return mixed
     * @throws Exception
     */
    public function includeFile($filename, $mode = self::MODE_REQUIRE)
    {
        $this->start();

        $realpath = realpath($filename);
        if (!file_exists($realpath)) {
            throw new Exception("Included file not found: \"{$filename}\"");
        }

        if (self::MODE_REQUIRE === $mode) {
            /** @noinspection PhpIncludeInspection */
            $result = require $realpath;
        } elseif (self::MODE_REQUIRE_ONCE === $mode) {
            /** @noinspection PhpIncludeInspection */
            /** @noinspection UsingInclusionOnceReturnValueInspection */
            $result = require_once $realpath;
        } else {
            throw new Exception("Undefined mode to include file: \"{$filename}\"");
        }

        $this->stop();

        return $result;
    }

    /**
     * @param null|string $testName
     * @return string
     */
    protected function getPrefix($testName = null)
    {
        if (null === $testName) {
            $objects = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);
            foreach ($objects as $object) {
                if (isset($object['object']) && $object['object'] instanceof TestCase) {
                    $testName = $object['class'] . '_' . $object['function'];
                    break;
                }
            }
        }

        $testName = str_replace(__NAMESPACE__ . '\\', '', $testName);
        $testName = Str::splitCamelCase($testName, '_', true);
        $testName = preg_replace('/^test_/', '', $testName);
        $testName = preg_replace('/_test$/', '', $testName);
        $testName = str_replace(['_test_test_', '/', '\\', '_', '-'], ['_', '', '', '', ''], $testName);
        $testName = strtolower($testName);

        if (!$testName) {
            $testName = uniqid('', true);
            $testName = str_replace('.', '', $testName);
        }

        return $testName;
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
        if (!$this->isStart && $this->coverage) {
            $this->isStart = true;
            $this->coverage->start($this->hash, true);
        }
    }

    /**
     * Stop or pause coverage process
     */
    protected function stop(): void
    {
        if ($this->isStart && $this->coverage) {
            $this->isStart = false;
            $this->coverage->stop();
        }
    }

    /**
     * Stop or pause coverage process
     */
    protected function createReports(): void
    {
        if (!$this->coverage) {
            return;
        }

        $reportXmlDir = $this->config->get('build_xml');
        if ($this->config->get('xml', true, 'bool')) {
            $this->checkDir($reportXmlDir);
            $report = new Clover();
            $report->process($this->coverage, $reportXmlDir . '/' . $this->hash . '.xml');
        }


        $reportCovDir = $this->config->get('build_cov');
        if ($this->config->get('cov', false, 'bool')) {
            $this->checkDir($reportCovDir);
            $report = new PHP();
            $report->process($this->coverage, $reportCovDir . '/' . $this->hash . '.cov');
        }


        $reportHtmlDir = $this->config->get('build_html');
        if ($this->config->get('html', false, 'bool')) {
            $this->checkDir($reportHtmlDir);
            $report = new Facade();
            $report->process($this->coverage, $reportHtmlDir . '/' . $this->hash);
        }
    }

    /**
     * @param string $dirPath
     */
    protected function checkDir($dirPath): void
    {
        if (!mkdir($dirPath, 0777, true) && !is_dir($dirPath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dirPath));
        }
    }

    /**
     * Prepare and init config
     * @param array $options
     */
    protected function initConfig(array $options): void
    {
        $options = array_filter($options, function ($option) {
            return null !== $option;
        });

        $this->config = new Data(array_merge($this->default, $options));
    }
}
