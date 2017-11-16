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

use JBZoo\Utils\Sys;
use JBZoo\Utils\FS;

/**
 * Class PHPUnitCovCatcherTest
 *
 * @package JBZoo\PHPUnit
 */
class PHPUnitCovCatcherTest extends PHPUnit
{
    protected function setUp()
    {
        parent::setUp();

        if (!class_exists(FS::class)) {
            throw new Exception('jbzoo/utils required for CovCatcher unit-tests');
        }

        FS::rmdir(PROJECT_BUILD . '/coverage_cov');
        FS::rmdir(PROJECT_BUILD . '/coverage_html');
        FS::rmdir(PROJECT_BUILD . '/coverage_xml');
    }

    public function testInclude()
    {
        $catcher = new CovCatcher(null, [
            'src'  => PROJECT_TESTS . '/fixtures/includes',
            'xml'  => true,
            'cov'  => true,
            'html' => true,
        ]);

        ob_start();
        $return = $catcher->includeFile(PROJECT_TESTS . '/fixtures/includes/cov-catcher.php');
        $echo = ob_get_contents();
        ob_end_clean();

        isSame(5, $return);
        isSame('Some text message', $echo);

        unset($catcher); // call destructor

        if (Sys::hasXdebug()) {
            isDir(PROJECT_BUILD . '/coverage_cov');
            isDir(PROJECT_BUILD . '/coverage_html');
            isDir(PROJECT_BUILD . '/coverage_xml');
        }
    }

    /**
     * @expectedException \Exception
     */
    public function testIncludeWithException()
    {
        $catcher = new CovCatcher(null, [
            'src'  => PROJECT_TESTS . '/fixtures/includes',
            'xml'  => true,
            'cov'  => true,
            'html' => true,
        ]);

        $catcher->includeFile(PROJECT_TESTS . '/fixtures/includes/cov-catcher-exception.php');
    }
}
