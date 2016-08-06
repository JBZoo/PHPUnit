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

use JBZoo\Utils\Env;
use JBZoo\Utils\Sys;

/**
 * Class ToolsTest
 * @package JBZoo\PHPUnit
 */
class ToolsTest extends PHPUnit
{
    public function testHttpRequest()
    {
        $uniq = uniqid();
        $url  = 'http://httpbin.org/post';
        $args = array('qwerty' => $uniq);

        $result = httpRequest($url, $args, 'post');

        isSame(200, $result->code);
        isContain('application/json', $result->find('headers.content-type'));

        $body = $result->getJSON();
        isSame($body->find('url'), $url);
        isSame($body->find('form.qwerty'), $uniq);
    }

    public function testLoopProfiler()
    {
        startProfiler();

        $max    = 100000;
        $result = array();
        for ($i = 0; $i < $max; $i++) {
            $result[] = $i;
        }

        // just for coverage :)
        loopProfiler($max, true);
        loopProfiler($max, false);
    }

    public function testXdebug()
    {
        isTrue(is_bool(isXDebug()));
    }

    public function testCmd()
    {
        if (!defined('HHVM_VERSION')) {
            $output = cmd('php', array('v' => ''));
            isContain('PHP', $output);

            $output = cmd('php', array('version' => ''));
            isContain('PHP', $output);
        }
    }

    public function testJBDumpForWeb()
    {
        if (!class_exists('\JBZoo\Utils\Sys')) {
            throw new Exception('jbzoo/utils required for Tools unit-tests');
        }

        if (Sys::isPhp53() || Sys::isPhp7() || Env::isHHVM()) {
            skip('PHP 5.3.x/7.0/hhvm doen\'t support built-in web-server');
        }

        $uniq   = uniqid();
        $result = httpRequest('http://localhost:8889/', array(
            'test'     => 'jbdump',
            'test-var' => $uniq
        ));

        isSame(200, $result->getCode());
        isContain('#jbdump', $result->getBody());
        isContain('Dump die', $result->getBody());
        isContain($uniq, $result->getBody());
    }

    /**
     * @expectedException \Exception
     */
    public function testCliDumpExitCode()
    {
        $scriptPath = realpath(PROJECT_ROOT . '/tests/webroot/index.php');
        cmd('php ' . $scriptPath);
    }

    public function testCliDump()
    {
        $uniq = uniqid();

        $scriptPath = realpath(PROJECT_ROOT . '/tests/webroot/index.php');
        $result     = cmd('php ' . $scriptPath . ' ' . $uniq);

        isContain('webroot/index.php', $result);
        isContain('cli arguments', $result);
        isContain($uniq, $result);
    }

    public function testCliError()
    {
        //cliError('Some error message');
    }
}
