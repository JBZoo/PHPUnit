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

use JBZoo\Utils\Url;

/**
 * Class ToolsTest
 * @package JBZoo\PHPUnit
 */
class HttpRequestTest extends PHPUnit
{
    protected function setUp()
    {
        parent::setUp();

        if (!class_exists('GuzzleHttp\Client')) {
            skip();
        }
    }

    public function testSimple()
    {
        $url    = 'http://www.mocky.io/v2/579b43a91100006f1bcb7734';
        $result = httpRequest($url);

        isSame(200, $result->code);
        isSame('42', $result->find('headers.x-custom-header'));
        isSame('application/json; charset=utf-8', $result->find('headers.content-type'));
        isSame('{"great-answer": "42"}', $result->body);
    }

    public function testGetQueryString()
    {
        $uniq   = uniqid();
        $url    = 'http://httpbin.org/get';
        $args   = array('qwerty' => $uniq);
        $result = httpRequest($url, $args, array(
            'method' => 'get'
        ));

        isSame(200, $result->code);
        isContain('application/json', $result->find('headers.content-type'));

        $body = $result->get('body', null, 'data');
        isSame(Url::addArg($args, $url), $body->find('url'));
        isSame($uniq, $body->find('args.qwerty'));
    }

    public function testPost()
    {
        $uniq = uniqid();
        $url  = 'http://httpbin.org/post';
        $args = array('qwerty' => $uniq);

        $result = httpRequest($url, $args, array(
            'method' => 'post'
        ));

        isSame(200, $result->code);
        isContain('application/json', $result->find('headers.content-type'));

        $body = $result->get('body', null, 'data');
        isSame($body->find('url'), $url);
        isSame($body->find('form.qwerty'), $uniq);
    }

    public function testStatus404()
    {
        $result = httpRequest('http://httpbin.org/status/404');

        isSame(404, $result->code);
    }

    public function testStatus500()
    {
        $result = httpRequest('http://httpbin.org/status/500');

        isSame(500, $result->code);
    }

    public function testRedirect()
    {
        $url = Url::addArg(array('url' => 'http://example.com'), 'http://httpbin.org/redirect-to');

        $result = httpRequest($url);

        isSame(200, $result->code);
        isContain('text/html', $result->find('headers.content-type'));
        isContain('Example', $result->body);
    }

    public function testHeaders()
    {
        $url = 'http://httpbin.org/headers';

        $uniq   = uniqid();
        $result = httpRequest($url, null, array(
            'headers' => array('X-Custom-Header' => $uniq)
        ));

        isSame(200, $result->code);

        $body = $result->get('body', null, 'data');

        isSame($uniq, $body->find('headers.X-Custom-Header'));
    }

    public function testGzip()
    {
        $url = 'http://httpbin.org/gzip';

        $result = httpRequest($url);

        isSame(200, $result->code);

        $body = $result->get('body', null, 'data');

        isSame(true, $body->find('gzipped'));
    }

    public function testMultiRedirects()
    {
        $url    = 'http://httpbin.org/absolute-redirect/10';
        $result = httpRequest($url);

        isSame(200, $result->code);

        $body = $result->get('body', null, 'data');
        isSame('http://httpbin.org/get', $body->get('url'));
    }

    /**
     * @expectedException \Exception
     */
    public function testDelayError()
    {
        httpRequest('http://httpbin.org/delay/5', null, array(
            'timeout' => 3
        ));
    }

    public function testDelay()
    {
        $url    = 'http://httpbin.org/delay/5';
        $result = httpRequest($url, null);

        isSame(200, $result->code);

        $body = $result->get('body', null, 'data');
        isSame($url, $body->get('url'));
    }

}
