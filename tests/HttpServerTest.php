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

use JBZoo\HttpClient\Response;
use JBZoo\Utils\Env;
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

        if (Sys::isPHP('5.3')) {
            skip('PHP 5.3.x doen\'t support built-in web-server');
        }

        FS::rmdir(PROJECT_BUILD . '/clover_cov');
        FS::rmdir(PROJECT_BUILD . '/clover_html');
        FS::rmdir(PROJECT_BUILD . '/clover_xml');
    }

    public function testSimple()
    {
        $uniq = uniqid();

        $result = $this->_httpRequest('http://localhost:8888/', array(
            'test' => $uniq
        ));

        isSame($uniq, $result->getBody());
        isSame(200, $result->getCode());

        if (Env::hasXdebug()) {
            isDir(PROJECT_BUILD . '/clover_cov');
            isDir(PROJECT_BUILD . '/clover_html');
            isDir(PROJECT_BUILD . '/clover_xml');
        }
    }

    public function testDirectIndex()
    {
        $uniq = uniqid();

        $result = $this->_httpRequest('http://localhost:8888/index.php', array(
            'test' => $uniq
        ));

        isSame($uniq, $result->getBody());
        isSame(200, $result->getCode());

        if (Env::hasXdebug()) {
            isDir(PROJECT_BUILD . '/clover_cov');
            isDir(PROJECT_BUILD . '/clover_html');
            isDir(PROJECT_BUILD . '/clover_xml');
        }
    }

    public function testNestedIndex()
    {
        $uniq = uniqid();

        $result = $this->_httpRequest('http://localhost:8888/folder/index-second.php', array(
            'test' => $uniq
        ));

        isSame($uniq, $result->getBody());
        isSame(200, $result->getCode());

        if (Env::hasXdebug()) {
            isDir(PROJECT_BUILD . '/clover_cov');
            isDir(PROJECT_BUILD . '/clover_html');
            isDir(PROJECT_BUILD . '/clover_xml');
        }
    }

    public function testAssets()
    {
        $result = $this->_httpRequest('http://localhost:8888/robots.txt');
        isSame(200, $result->getCode());
        isContain('User-agent: *', $result->getBody());

        $result = $this->_httpRequest('http://localhost:8888/robots.txt', array('test' => '123456'));
        isSame(200, $result->getCode());
        isContain('User-agent: *', $result->getBody());
    }

    /**
     * @param string $url
     * @param array  $args
     * @return Response
     */
    protected function _httpRequest($url, $args = array())
    {
        return httpRequest($url, $args);
    }
}
