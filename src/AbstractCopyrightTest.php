<?php

/**
 * JBZoo Toolbox - PHPUnit
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    PHPUnit
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/PHPUnit
 */

declare(strict_types=1);

namespace JBZoo\PHPUnit;

use Symfony\Component\Finder\Finder;

/**
 * Class AbstractCopyrightTest
 *
 * @package JBZoo\PHPUnit
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
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
     * @var bool
     */
    protected $isPhpStrictType = false;

    /**
     * @var string
     */
    protected $projectRoot = PROJECT_ROOT;

    /**
     * @var string
     */
    protected $packageVendor = 'JBZoo Toolbox';

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
        'This file is part of the _VENDOR_ project.',
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

    #### Patterns of copyrights. #######################################################################################

    /**
     * @var string[]
     */
    protected $validHeaderPHP = [
        '/**',
        ' * _VENDOR_ - _PACKAGE_',
        ' *',
        ' * _DESCRIPTION_PHP_',
        ' *',
        ' * @package    _PACKAGE_',
        ' * @license    _LICENSE_',
        ' * @copyright  _COPYRIGHTS_',
        ' * @link       _LINK_',
    ];

    /**
     * @var string[]
     */
    protected $validHeaderJS = [
        '/**',
        ' * _VENDOR_ - _PACKAGE_',
        ' *',
        ' * _DESCRIPTION_JS_',
        ' *',
        ' * @package    _PACKAGE_',
        ' * @license    _LICENSE_',
        ' * @copyright  _COPYRIGHTS_',
        ' * @link       _LINK_',
    ];

    /**
     * @var string[]
     */
    protected $validHeaderCSS = [
        '/**',
        ' * _VENDOR_ - _PACKAGE_',
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
     * @var string[]
     */
    protected $validHeaderLESS = [
        '//',
        '// _VENDOR_ - _PACKAGE_',
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
     * @var string[]
     */
    protected $validHeaderXML = [
        '<?xml version="1.0" encoding="UTF-8" ?>',
        '<!--',
        '    _VENDOR_ - _PACKAGE_',
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
     * @var string[]
     */
    protected $validHeaderINI = [
        ';',
        '; _VENDOR_ - _PACKAGE_',
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
     * @var string[]
     */
    protected $validHeaderSH = [
        '#!/usr/bin/env sh',
        '',
        '#',
        '# _VENDOR_ - _PACKAGE_',
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
     * @var string[]
     */
    protected $validHeaderSQL = [
        '--',
        '-- _VENDOR_ - _PACKAGE_',
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
     * @var string[]
     */
    protected $validHeaderHash = [
        '#',
        '# _VENDOR_ - _PACKAGE_',
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

        $this->projectRoot = trim($this->projectRoot);

        if (!$this->packageName) {
            throw new Exception('$this->packageName is undefined!');
        }

        if ('' === $this->projectRoot) {
            throw new Exception('$this->projectRoot is undefined!');
        }

        if (!class_exists(Finder::class)) {
            throw new Exception('symfony/finder is required for CodeStyle unit tests');
        }
    }

    public function testHeadersPhp(): void
    {
        $valid = $this->prepareTemplate(implode($this->eol, $this->validHeaderPHP));

        if ($this->isPsr12) {
            $valid = "<?php{$this->eol}{$this->eol}{$valid}";
        } else {
            $valid = "<?php{$this->eol}{$valid}";
        }

        if ($this->isPhpStrictType) {
            $valid .= implode($this->eol, [
                '',
                ' */',
                '',
                'declare(strict_types=1);',
                ''
            ]);
        }

        $finder = $this->createFinder(['php', 'phtml']);

        self::checkHeaderInFiles($finder, $valid);
    }

    public function testHeadersJs(): void
    {
        $finder = $this->createFinder(['js', 'jsx'], ['*.min.js', '*.min.jsx']);

        self::checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderJS)));
    }

    public function testHeadersCss(): void
    {
        $finder = $this->createFinder(['css'], ['*.min.css']);

        self::checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderCSS)));
    }

    public function testHeadersLess(): void
    {
        $finder = $this->createFinder(['less']);

        self::checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderLESS)));
    }

    public function testHeadersXml(): void
    {
        $finder = $this->createFinder(['xml.dist', 'xml']);

        self::checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderXML)));
    }

    /**
     * Test copyright headers of INI files
     */
    public function testHeadersIni(): void
    {
        $finder = $this->createFinder(['ini']);

        self::checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderINI)));
    }

    /**
     * Test copyright headers of SH files
     */
    public function testHeadersSh(): void
    {
        $finder = $this->createFinder(['sh']);

        self::checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderSH)));
    }

    /**
     * Test copyright headers of SQL files
     */
    public function testHeadersSql(): void
    {
        $finder = $this->createFinder(['sql']);

        self::checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderSQL)));
    }

    /**
     * Test copyright headers of Makefile
     */
    public function testHeadersMakefile(): void
    {
        $finder = $this->createFinder(['Makefile']);
        $finder->name('Makefile');

        self::checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderHash)));
    }

    /**
     * Test copyright headers of Makefile
     */
    public function testHeadersYaml(): void
    {
        $finder = $this->createFinder(['yml', 'yaml', 'neon']);

        self::checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderHash)));
    }

    /**
     * Test copyright headers of .htaccess files
     */
    public function testHeadersHtaccess(): void
    {
        $finder = $this->createFinder(['htaccess', '.htaccess']);

        self::checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderHash)));
    }

    /**
     * Test copyright headers of .htaccess files
     */
    public function testHeadersConfigs(): void
    {
        $finder = $this->createFinder(['editorconfig', 'gitattributes', 'gitignore']);

        self::checkHeaderInFiles($finder, $this->prepareTemplate(implode($this->eol, $this->validHeaderHash)));
    }

    #### Internal tools for test case ##################################################################################

    /**
     * @param array<string> $inclusions
     * @param array<string> $exclusions
     * @return Finder
     */
    protected function createFinder(array $inclusions = [], array $exclusions = []): Finder
    {
        $finder = (new Finder())
            ->files()
            ->in($this->projectRoot)
            ->exclude($this->excludePaths)
            ->ignoreDotFiles(false)
            ->followLinks();

        foreach ($inclusions as $inclusion) {
            $finder
                ->name("\.{$inclusion}")
                ->name("*\.{$inclusion}")
                ->name("\.*\.{$inclusion}");
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
    protected function prepareTemplate(string $text): string
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
    protected static function checkHeaderInFiles(Finder $finder, string $validHeader): void
    {
        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            if ($content) {
                $isValid = str_starts_with($content, $validHeader);

                $errMessage = implode("\n", [
                    "The file has no valid copyright in header",
                    "See: {$file}",
                    "Expected file header:",
                    str_repeat('-', 80),
                    $validHeader,
                    str_repeat('-', 80)
                ]);

                isTrue($isValid, $errMessage);
            }
        }

        isTrue(true); // One assert is a minimum to complete test
    }
}
