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

declare(strict_types=1);

namespace JBZoo\PHPUnit;

use SplFileInfo;
use Symfony\Component\Finder\Finder;

/**
 * Class AbstractCopyrightTest
 *
 * @package JBZoo\PHPUnit
 */
abstract class AbstractCopyrightTest extends PHPUnit
{
    #### Configurations. Override it if you need for your project. #####################################################

    /**
     * @var string
     */
    protected $packageName = ''; // Overload me!

    /**
     * @var bool
     */
    protected $isPsr12 = true;

    /**
     * @var string
     */
    protected $projectRoot = PROJECT_ROOT;

    /**
     * @var string
     */
    protected $packageVendor = 'JBZoo';

    /**
     * @var string
     */
    protected $packageLicense = 'MIT';

    /**
     * @var string
     */
    protected $packageCopyright = 'Copyright (C) JBZoo.com, All rights reserved.';

    /**
     * @var string
     */
    protected $packageLink = 'https://github.com/JBZoo/_PACKAGE_';

    /**
     * @var string
     */
    protected $packageAuthor = '';

    /**
     * @var string[]
     */
    protected $packageDesc = [
        'This file is part of the JBZoo CCK package.',
        'For the full copyright and license information, please view the LICENSE',
        'file that was distributed with this source code.',
    ];

    /**
     * @var string
     */
    protected $eol = "\n";

    /**
     * @var string[]
     */
    protected $excludePaths = [
        '.git',
        '.idea',
        'bin',
        'bower_components',
        'build',
        'fonts',
        'fixtures',
        'logs',
        'node_modules',
        'resources',
        'vendor',
        'temp',
        'tmp',
    ];

    #### Paterns of copyrights. ########################################################################################

    /**
     * Valid header for PHP files
     *
     * @var string[]
     */
    protected $validHeaderPHP = [
        '/**',
        ' * _VENDOR_ _PACKAGE_',
        ' *',
        ' * _DESCRIPTION_PHP_',
        ' *',
        ' * @package    _PACKAGE_',
        ' * @license    _LICENSE_',
        ' * @copyright  _COPYRIGHTS_',
        ' * @link       _LINK_',
    ];

    /**
     * Valid header for JavaScript files
     *
     * @var string[]
     */
    protected $validHeaderJS = [
        '/**',
        ' * _VENDOR_ _PACKAGE_',
        ' *',
        ' * _DESCRIPTION_JS_',
        ' *',
        ' * @package    _PACKAGE_',
        ' * @license    _LICENSE_',
        ' * @copyright  _COPYRIGHTS_',
        ' * @link       _LINK_',
        ' */',
        '',
    ];

    /**
     * Valid header for CSS files (if not mimified)
     *
     * @var string[]
     */
    protected $validHeaderCSS = [
        '/**',
        ' * _VENDOR_ _PACKAGE_',
        ' *',
        ' * _DESCRIPTION_CSS_',
        ' *',
        ' * @package    _PACKAGE_',
        ' * @license    _LICENSE_',
        ' * @copyright  _COPYRIGHTS_',
        ' * @link       _LINK_',
        ' */',
        '',
    ];

    /**
     * Valid header for LESS files
     *
     * @var string[]
     */
    protected $validHeaderLESS = [
        '//',
        '// _VENDOR_ _PACKAGE_',
        '//',
        '// _DESCRIPTION_LESS_',
        '//',
        '// @package    _PACKAGE_',
        '// @license    _LICENSE_',
        '// @copyright  _COPYRIGHTS_',
        '// @link       _LINK_',
        '//',
    ];

    /**
     * Valid header for XML files
     *
     * @var string[]
     */
    protected $validHeaderXML = [
        '<?xml version="1.0" encoding="UTF-8" ?>',
        '<!--',
        '    _VENDOR_ _PACKAGE_',
        '',
        '    _DESCRIPTION_XML_',
        '',
        '    @package    _PACKAGE_',
        '    @license    _LICENSE_',
        '    @copyright  _COPYRIGHTS_',
        '    @link       _LINK_',
        '-->',
    ];

    /**
     * Valid header for INI files
     *
     * @var string[]
     */
    protected $validHeaderINI = [
        ';',
        '; _VENDOR_ _PACKAGE_',
        ';',
        '; _DESCRIPTION_INI_',
        ';',
        '; @package    _PACKAGE_',
        '; @license    _LICENSE_',
        '; @copyright  _COPYRIGHTS_',
        '; @link       _LINK_',
        ';',
        '; Note: All ini files need to be saved as UTF-8 (no BOM)',
        ';',
    ];

    /**
     * Valid header for SH scripts
     *
     * @var string[]
     */
    protected $validHeaderSH = [
        '#!/usr/bin/env sh',
        '',
        '#',
        '# _VENDOR_ _PACKAGE_',
        '#',
        '# _DESCRIPTION_SH_',
        '#',
        '# @package    _PACKAGE_',
        '# @license    _LICENSE_',
        '# @copyright  _COPYRIGHTS_',
        '# @link       _LINK_',
        '#',
        '',
    ];

    /**
     * Valid header for SQL scripts
     *
     * @var string[]
     */
    protected $validHeaderSQL = [
        '--',
        '-- _VENDOR_ _PACKAGE_',
        '--',
        '-- _DESCRIPTION_SQL_',
        '--',
        '-- @package    _PACKAGE_',
        '-- @license    _LICENSE_',
        '-- @copyright  _COPYRIGHTS_',
        '-- @link       _LINK_',
        '--',
        '',
    ];

    /**
     * Valid header for .htaccess scripts
     *
     * @var string[]
     */
    protected $validHeaderHash = [
        '#',
        '# _VENDOR_ _PACKAGE_',
        '#',
        '# _DESCRIPTION_HTACCESS_',
        '#',
        '# @package    _PACKAGE_',
        '# @license    _LICENSE_',
        '# @copyright  _COPYRIGHTS_',
        '# @link       _LINK_',
        '#',
        '',
    ];

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (!$this->packageName) {
            throw new Exception('$this->packageName is undefined!');
        }

        if (!$this->projectRoot) {
            throw new Exception('$this->projectRoot is undefined!');
        }

        if (!class_exists(Finder::class)) {
            throw new Exception('symfony/finder is required for CodeStyle unit tests');
        }
    }

    /**
     * Test copyright headers of PHP files
     */
    public function testHeadersPHP(): void
    {
        $valid = $this->prepareTemplate(implode($this->eol, $this->validHeaderPHP));

        if ($this->isPsr12) {
            $valid = "<?php{$this->eol}{$this->eol}{$valid}";
        } else {
            $valid = "<?php{$this->eol}{$valid}";
        }

        $finder = $this->createFinder(['php', 'phtml']);

        $this->checkHeaderInFiles($finder, $valid);
    }

    /**
     * Test copyright headers of JS files (not minified)
     */
    public function testHeadersJS(): void
    {
        $finder = $this->createFinder(['js', 'jsx'], ['*.min.js', '*.min.jsx']);

        $this->checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderJS)));
    }

    /**
     * Test copyright headers of CSS files (not minified)
     */
    public function testHeadersCSS(): void
    {
        $finder = $this->createFinder(['css'], ['*.min.css']);

        $this->checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderCSS)));
    }

    /**
     * Test copyright headers of LESS files (not minified)
     */
    public function testHeadersLESS(): void
    {
        $finder = $this->createFinder(['less']);

        $this->checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderLESS)));
    }

    /**
     * Test copyright headers of XML files
     */
    public function testHeadersXML(): void
    {
        $finder = $this->createFinder(['xml.dist', 'xml']);

        $this->checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderXML)));
    }

    /**
     * Test copyright headers of INI files
     */
    public function testHeadersINI(): void
    {
        $finder = $this->createFinder(['ini']);

        $this->checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderINI)));
    }

    /**
     * Test copyright headers of SH files
     */
    public function testHeadersSH(): void
    {
        $finder = $this->createFinder(['sh']);

        $this->checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderSH)));
    }

    /**
     * Test copyright headers of SQL files
     */
    public function testHeadersSQL(): void
    {
        $finder = $this->createFinder(['sql']);

        $this->checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderSQL)));
    }

    /**
     * Test copyright headers of Makefile
     */
    public function testHeadersMakefile(): void
    {
        $finder = $this->createFinder(['Makefile']);

        $this->checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderHash)));
    }

    /**
     * Test copyright headers of Makefile
     */
    public function testHeadersYaml(): void
    {
        $finder = $this->createFinder(['yml', 'yaml', 'neon']);

        $this->checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderHash)));
    }

    /**
     * Test copyright headers of .htaccess files
     */
    public function testHeadersHtaccess(): void
    {
        $finder = $this->createFinder(['htaccess', '.htaccess']);

        $this->checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderHash)));
    }

    /**
     * Test copyright headers of .htaccess files
     */
    public function testHeadersConfigs(): void
    {
        $finder = $this->createFinder(['editorconfig', 'gitattributes', 'gitignore']);

        $this->checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderHash)));
    }

    #### Internal tools for test case ##################################################################################

    /**
     * @param array<string> $inclusions
     * @param array<string> $exclusions
     * @return Finder
     */
    protected function createFinder(array $inclusions = [], array $exclusions = [])
    {
        $finder = (new Finder())
            ->files()
            ->in($this->projectRoot)
            ->exclude($this->excludePaths)
            ->ignoreDotFiles(false)
            ->followLinks();

        foreach ($inclusions as $inclusion) {
            $finder
                ->name(".{$inclusion}")
                ->name("*.{$inclusion}")
                ->name(".*.{$inclusion}");
        }

        foreach ($exclusions as $exclusion) {
            $finder->notName($exclusion);
        }

        return $finder;
    }

    /**
     * Render copyrights
     *
     * @param string $text
     * @return string
     */
    protected function prepareTemplate($text): string
    {
        $replace = [
            '_DESCRIPTION_PHP_'      => implode($this->eol . ' * ', $this->packageDesc),
            '_DESCRIPTION_JS_'       => implode($this->eol . ' * ', $this->packageDesc),
            '_DESCRIPTION_CSS_'      => implode($this->eol . ' * ', $this->packageDesc),
            '_DESCRIPTION_LESS_'     => implode($this->eol . '// ', $this->packageDesc),
            '_DESCRIPTION_XML_'      => implode($this->eol . '    ', $this->packageDesc),
            '_DESCRIPTION_INI_'      => implode($this->eol . '; ', $this->packageDesc),
            '_DESCRIPTION_SH_'       => implode($this->eol . '# ', $this->packageDesc),
            '_DESCRIPTION_SQL_'      => implode($this->eol . '-- ', $this->packageDesc),
            '_DESCRIPTION_HTACCESS_' => implode($this->eol . '# ', $this->packageDesc),
            '_LINK_'                 => $this->packageLink,
            '_NAMESPACE_'            => '_VENDOR_\_PACKAGE_',
            '_COPYRIGHTS_'           => $this->packageCopyright,
            '_PACKAGE_'              => $this->packageName,
            '_LICENSE_'              => $this->packageLicense,
            '_AUTHOR_'               => $this->packageAuthor,
            '_VENDOR_'               => $this->packageVendor,
        ];

        foreach ($replace as $const => $value) {
            $text = str_replace($const, $value, $text);
        }

        return $text;
    }

    /**
     * @param Finder $finder
     * @param string $validHeader
     */
    protected function checkHeaderInFiles(Finder $finder, string $validHeader): void
    {
        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            if ($content) {
                $isValid = strpos($content, $validHeader) === 0;

                $errMessage = implode("\n", [
                    "The file has no valid copyright in header",
                    $file,
                    "Expected file header (copyright):",
                    str_repeat('-', 60),
                    $validHeader,
                    str_repeat('-', 60)
                ]);

                isTrue($isValid, $errMessage);
            }
        }

        isTrue(true); // One assert is a minimum to complete test
    }
}
