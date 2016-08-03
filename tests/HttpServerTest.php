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
use JBZoo\Utils\FS;

/**
 * Class HttpServerTest
 * @package JBZoo\PHPUnit
 */
class HttpServerTest extends PHPUnit
{
    protected function setUp()
    {
        parent::setUp();

        FS::rmdir(PROJECT_BUILD . '/clover_cov');
        FS::rmdir(PROJECT_BUILD . '/clover_html');
        FS::rmdir(PROJECT_BUILD . '/clover_xml');
    }

    public function testSimple()
    {
        $uniq = uniqid();

        $result = $this->_httpRequest('http://127.0.0.1:8081/', array(
            'test' => $uniq
        ));

        isSame($uniq, $result->getBody());
        isSame(200, $result->getCode());

        isDir(PROJECT_BUILD . '/clover_cov');
        isDir(PROJECT_BUILD . '/clover_html');
        isDir(PROJECT_BUILD . '/clover_xml');
    }

    public function testDirectIndex()
    {
        $uniq = uniqid();

        $result = $this->_httpRequest('http://127.0.0.1:8081/index.php', array(
            'test' => $uniq
        ));

        isSame($uniq, $result->getBody());
        isSame(200, $result->getCode());

        isDir(PROJECT_BUILD . '/clover_cov');
        isDir(PROJECT_BUILD . '/clover_html');
        isDir(PROJECT_BUILD . '/clover_xml');
    }

    public function testNestedIndex()
    {
        $uniq = uniqid();

        $result = $this->_httpRequest('http://127.0.0.1:8081/folder/index-second.php', array(
            'test' => $uniq
        ));

        isSame($uniq, $result->getBody());
        isSame(200, $result->getCode());

        isDir(PROJECT_BUILD . '/clover_cov');
        isDir(PROJECT_BUILD . '/clover_html');
        isDir(PROJECT_BUILD . '/clover_xml');
    }

    public function testAssets()
    {
        $result = $this->_httpRequest('http://127.0.0.1:8081/robots.txt');

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
        $result = file_get_contents($url . '?' . http_build_query($args));

        $response = new Response();
        $response->setCode(200);
        $response->setBody($result);

        return $response;
    }
}
