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

use JBZoo\HttpClient\Response;
use JBZoo\Utils\FS;
use JBZoo\Utils\Sys;

class PHPUnitHttpServerTest extends PHPUnit
{
    protected function setUp(): void
    {
        parent::setUp();

        FS::rmDir(PROJECT_BUILD . '/coverage_cov');
        FS::rmDir(PROJECT_BUILD . '/coverage_html');
        FS::rmDir(PROJECT_BUILD . '/coverage_xml');
    }

    public function testSimple(): void
    {
        if (Sys::isPHP('7.4')) {
            skip('Not supported yet in PHP 7.4');
        }

        $uniq = \uniqid('', true);

        $result = $this->httpRequest('http://localhost:8888/', [
            'test' => $uniq,
        ]);

        isSame($uniq, $result->getBody());
        isSame(200, $result->getCode());

        if (Sys::hasXdebug()) {
            isDir(PROJECT_BUILD . '/coverage_cov');
            isDir(PROJECT_BUILD . '/coverage_html');
            isDir(PROJECT_BUILD . '/coverage_xml');
        }
    }

    public function testDirectIndex(): void
    {
        $uniq = \uniqid('', true);

        $result = $this->httpRequest('http://localhost:8888/index.php', [
            'test' => $uniq,
        ]);

        isSame($uniq, $result->getBody());
        isSame(200, $result->getCode());

        if (Sys::hasXdebug()) {
            isDir(PROJECT_BUILD . '/coverage_cov');
            isDir(PROJECT_BUILD . '/coverage_html');
            isDir(PROJECT_BUILD . '/coverage_xml');
        }
    }

    public function testNestedIndex(): void
    {
        $uniq = \uniqid('', true);

        $result = $this->httpRequest('http://localhost:8888/folder/index-second.php', [
            'test' => $uniq,
        ]);

        isSame($uniq, $result->getBody());
        isSame(200, $result->getCode());

        if (Sys::hasXdebug()) {
            isDir(PROJECT_BUILD . '/coverage_cov');
            isDir(PROJECT_BUILD . '/coverage_html');
            isDir(PROJECT_BUILD . '/coverage_xml');
        }
    }

    public function testAssets(): void
    {
        $result = $this->httpRequest('http://localhost:8888/robots.txt');
        isSame(200, $result->getCode());
        // isContain('User-agent: *', $result->getBody());

        $result = $this->httpRequest('http://localhost:8888/robots.txt', ['test' => '123456']);
        isSame(200, $result->getCode());
        // isContain('User-agent: *', $result->getBody());
    }

    /**
     * @param  string                      $url
     * @param  array                       $args
     * @throws Exception
     * @throws \JBZoo\HttpClient\Exception
     */
    protected function httpRequest($url, $args = []): Response
    {
        return httpRequest($url, $args);
    }
}
