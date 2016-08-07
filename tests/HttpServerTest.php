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

namespace JBZoo\PHPUnit;

use JBZoo\Utils\FS;
use JBZoo\Utils\Sys;

/**
 * Class HttpServerTest
 * @package JBZoo\PHPUnit
 */
class HttpServerTest extends PHPUnit
{
    protected function setUp()
    {
        parent::setUp();

        if (!class_exists('\JBZoo\Utils\Sys')) {
            throw new Exception('jbzoo/utils required for HttpServer unit-tests');
        }

        if (!class_exists('\JBZoo\HttpClient\HttpClient')) {
            throw new Exception('jbzoo/http-client required for HttpServer unit-tests');
        }

        if (Sys::isPHP53() || Sys::isHHVM()) {
            skip('PHP 5.3.x/7.0/hhvm doen\'t support built-in web-server');
        }

        FS::rmdir(PROJECT_BUILD . '/coverage_cov');
        FS::rmdir(PROJECT_BUILD . '/coverage_html');
        FS::rmdir(PROJECT_BUILD . '/coverage_xml');
    }

    public function testSimple()
    {
        $uniq = uniqid();

        $result = httpRequest('http://localhost:8888/', array('test' => $uniq));

        isSame('index: ' . $uniq, $result->getBody());
        isSame(200, $result->getCode());

        if (Sys::hasXdebug() && !Sys::isPHP7()) {
            isDir(PROJECT_BUILD . '/coverage_cov');
            isDir(PROJECT_BUILD . '/coverage_html');
            isDir(PROJECT_BUILD . '/coverage_xml');
        }
    }

    public function testDirectIndex()
    {
        $uniq = uniqid();

        $result = httpRequest('http://localhost:8888', array('test' => $uniq));

        isSame('index: ' . $uniq, $result->getBody());
        isSame(200, $result->getCode());

        if (Sys::hasXdebug() && !Sys::isPHP7()) {
            isDir(PROJECT_BUILD . '/coverage_cov');
            isDir(PROJECT_BUILD . '/coverage_html');
            isDir(PROJECT_BUILD . '/coverage_xml');
        }
    }

    public function testNestedIndexCoverage()
    {
        $uniq = uniqid();

        $result = httpRequest('http://localhost:8888/folder/not-index.php', array('test' => $uniq));

        isSame('folder/not-index: ' . $uniq, $result->getBody());
        isSame(200, $result->getCode());

        if (Sys::hasXdebug() && !Sys::isPHP7()) {
            isDir(PROJECT_BUILD . '/coverage_cov');
            isDir(PROJECT_BUILD . '/coverage_html');
            isDir(PROJECT_BUILD . '/coverage_xml');
        }
    }

    public function testIndex()
    {
        $uniq = uniqid();

        $result = httpRequest('http://localhost:8888');
        isSame(200, $result->getCode());
        isContain('text/html', $result->getHeader('content-type'));
        isSame('index: undefined!', $result->getBody());

        $result = httpRequest('http://localhost:8888', ['test' => $uniq]);
        isSame(200, $result->getCode());
        isContain('text/html', $result->getHeader('content-type'));
        isSame('index: ' . $uniq, $result->getBody());

        $result = httpRequest('http://localhost:8888/index.php');
        isSame(200, $result->getCode());
        isContain('text/html', $result->getHeader('content-type'));
        isSame('index: undefined!', $result->getBody());

        $result = httpRequest('http://localhost:8888/index.php', ['test' => $uniq]);
        isSame(200, $result->getCode());
        isContain('text/html', $result->getHeader('content-type'));
        isSame('index: ' . $uniq, $result->getBody());
    }

    public function testIndexStatic()
    {
        $result = httpRequest('http://localhost:8888/file.txt');
        isSame(200, $result->getCode());
        isContain('text/plain', $result->getHeader('content-type'));
        isSame('static file', $result->getBody());

        $result = httpRequest('http://localhost:8888/file.txt', array('key' => 'value'));
        isSame(200, $result->getCode());
        isContain('text/plain', $result->getHeader('content-type'));
        isSame('static file', $result->getBody());
    }

    public function testNestedIndex()
    {
        $uniq = uniqid();

        $result = httpRequest('http://localhost:8888/folder');
        isSame(200, $result->getCode());
        isContain('text/html', $result->getHeader('content-type'));
        isSame('folder/index: undefined!', $result->getBody());

        $result = httpRequest('http://localhost:8888/folder', array('test' => $uniq));
        isSame(200, $result->getCode());
        isContain('text/html', $result->getHeader('content-type'));
        isSame('folder/index: ' . $uniq, $result->getBody());

        $result = httpRequest('http://localhost:8888/folder/index.php');
        isSame(200, $result->getCode());
        isContain('text/html', $result->getHeader('content-type'));
        isSame('folder/index: undefined!', $result->getBody());

        $result = httpRequest('http://localhost:8888/folder/index.php', array('test' => $uniq));
        isSame(200, $result->getCode());
        isContain('text/html', $result->getHeader('content-type'));
        isSame('folder/index: ' . $uniq, $result->getBody());
    }

    public function testNestedStatic()
    {
        $result = httpRequest('http://localhost:8888/folder/file.txt');
        isSame(200, $result->getCode());
        isContain('text/plain', $result->getHeader('content-type'));
        isSame('static folder/file', $result->getBody());

        $result = httpRequest('http://localhost:8888/folder/file.txt', array('key' => 'value'));
        isSame(200, $result->getCode());
        isContain('text/plain', $result->getHeader('content-type'));
        isSame('static folder/file', $result->getBody());
    }
}
