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

use JBZoo\HttpClient\Response;
use JBZoo\Utils\FS;
use JBZoo\Utils\Sys;
use JBZoo\HttpClient\HttpClient;

/**
 * Class HttpServerTest
 *
 * @package JBZoo\PHPUnit
 */
class HttpServerTest extends PHPUnit
{
    protected function setUp()
    {
        parent::setUp();

        if (!class_exists(Sys::class)) {
            throw new Exception('jbzoo/utils required for HttpServer unit-tests');
        }

        if (!class_exists(HttpClient::class)) {
            throw new Exception('jbzoo/http-client required for HttpServer unit-tests');
        }

        FS::rmdir(PROJECT_BUILD . '/coverage_cov');
        FS::rmdir(PROJECT_BUILD . '/coverage_html');
        FS::rmdir(PROJECT_BUILD . '/coverage_xml');
    }

    public function testSimple()
    {
        $uniq = uniqid('', true);

        $result = $this->httpRequest('http://localhost:8888/', [
            'test' => $uniq,
        ]);

        isSame($uniq, $result->getBody());
        isSame(200, $result->getCode());

        if (Sys::hasXdebug() && !Sys::isPHP7()) {
            isDir(PROJECT_BUILD . '/coverage_cov');
            isDir(PROJECT_BUILD . '/coverage_html');
            isDir(PROJECT_BUILD . '/coverage_xml');
        }
    }

    public function testDirectIndex()
    {
        $uniq = uniqid('', true);

        $result = $this->httpRequest('http://localhost:8888/index.php', [
            'test' => $uniq,
        ]);

        isSame($uniq, $result->getBody());
        isSame(200, $result->getCode());

        if (Sys::hasXdebug() && !Sys::isPHP7()) {
            isDir(PROJECT_BUILD . '/coverage_cov');
            isDir(PROJECT_BUILD . '/coverage_html');
            isDir(PROJECT_BUILD . '/coverage_xml');
        }
    }

    public function testNestedIndex()
    {
        $uniq = uniqid('', true);

        $result = $this->httpRequest('http://localhost:8888/folder/index-second.php', [
            'test' => $uniq,
        ]);

        isSame($uniq, $result->getBody());
        isSame(200, $result->getCode());

        if (Sys::hasXdebug() && !Sys::isPHP7()) {
            isDir(PROJECT_BUILD . '/coverage_cov');
            isDir(PROJECT_BUILD . '/coverage_html');
            isDir(PROJECT_BUILD . '/coverage_xml');
        }
    }

    public function testAssets()
    {
        $result = $this->httpRequest('http://localhost:8888/robots.txt');
        isSame(200, $result->getCode());
        //isContain('User-agent: *', $result->getBody());

        $result = $this->httpRequest('http://localhost:8888/robots.txt', ['test' => '123456']);
        isSame(200, $result->getCode());
        //isContain('User-agent: *', $result->getBody());
    }

    /**
     * @param string $url
     * @param array  $args
     * @return Response
     */
    protected function httpRequest($url, $args = []): Response
    {
        return httpRequest($url, $args);
    }
}
