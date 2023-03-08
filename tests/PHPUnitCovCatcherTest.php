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

use JBZoo\Utils\FS;
use JBZoo\Utils\Sys;

class PHPUnitCovCatcherTest extends PHPUnit
{
    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (!\class_exists(FS::class)) {
            throw new Exception('jbzoo/utils required for CovCatcher unit-tests');
        }

        FS::rmDir(PROJECT_BUILD . '/coverage_cov');
        FS::rmDir(PROJECT_BUILD . '/coverage_html');
        FS::rmDir(PROJECT_BUILD . '/coverage_xml');
    }

    /**
     * @throws Exception
     * @throws \ReflectionException
     */
    public function testInclude(): void
    {
        $catcher = new CovCatcher('', [
            'src'  => PROJECT_TESTS . '/fixtures/includes',
            'xml'  => true,
            'cov'  => true,
            'html' => true,
        ]);

        \ob_start();
        $return = $catcher->includeFile(PROJECT_TESTS . '/fixtures/includes/cov-catcher.php');
        $echo   = \ob_get_clean();

        isSame(5, $return);
        isSame('Some text message', $echo);

        unset($catcher); // call destructor

        if (Sys::hasXdebug()) {
            isDir(PROJECT_BUILD . '/coverage_cov');
            isDir(PROJECT_BUILD . '/coverage_html');
            isDir(PROJECT_BUILD . '/coverage_xml');
        }
    }

    public function testIncludeWithException(): void
    {
        $this->expectException(\RuntimeException::class);

        $catcher = new CovCatcher('', [
            'src'  => PROJECT_TESTS . '/fixtures/includes',
            'xml'  => true,
            'cov'  => true,
            'html' => true,
        ]);

        $catcher->includeFile(PROJECT_TESTS . '/fixtures/includes/cov-catcher-exception.php');
    }
}
