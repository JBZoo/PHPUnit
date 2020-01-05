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

use SplFileInfo;
use Symfony\Component\Finder\Finder;

/**
 * Class Codestyle
 *
 * @package JBZoo\PHPUnit
 */
abstract class Codestyle extends PHPUnit
{
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
     * @var array
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
     * @var array
     */
    protected $replace = [];

    /**
     * Ignore list for
     *
     * @var array
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

    /**
     * Valid header for PHP files
     *
     * @var array
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
     * @var array
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
     * @var array
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
     * @var array
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
     * @var array
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
     * @var array
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
     * @var array
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
     * @var array
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
     * @var array
     */
    protected $validHeaderHtaccess = [
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
            throw new Exception('$this->_packageName is undefined!');
        }

        if (!class_exists(Finder::class)) {
            throw new Exception('symfony/finder requreid for CodeStyle unit tests');
        }

        $this->replace = [
            '_LINK_'                 => $this->packageLink,
            '_NAMESPACE_'            => '_VENDOR_\_PACKAGE_',
            '_COPYRIGHTS_'           => $this->packageCopyright,
            '_PACKAGE_'              => $this->packageName,
            '_LICENSE_'              => $this->packageLicense,
            '_AUTHOR_'               => $this->packageAuthor,
            '_VENDOR_'               => $this->packageVendor,
            '_DESCRIPTION_PHP_'      => implode($this->eol . ' * ', $this->packageDesc),
            '_DESCRIPTION_JS_'       => implode($this->eol . ' * ', $this->packageDesc),
            '_DESCRIPTION_CSS_'      => implode($this->eol . ' * ', $this->packageDesc),
            '_DESCRIPTION_LESS_'     => implode($this->eol . '// ', $this->packageDesc),
            '_DESCRIPTION_XML_'      => implode($this->eol . '    ', $this->packageDesc),
            '_DESCRIPTION_INI_'      => implode($this->eol . '; ', $this->packageDesc),
            '_DESCRIPTION_SH_'       => implode($this->eol . '# ', $this->packageDesc),
            '_DESCRIPTION_SQL_'      => implode($this->eol . '-- ', $this->packageDesc),
            '_DESCRIPTION_HTACCESS_' => implode($this->eol . '# ', $this->packageDesc),
        ];
    }

    /**
     * Test line endings
     */
    public function testFiles(): void
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->name('*.html')
            ->name('*.xml')
            ->name('*.js')
            ->name('*.jsx')
            ->name('*.css')
            ->name('*.less')
            ->name('*.php')
            ->name('*.phtml')
            ->name('*.ini')
            ->name('*.json')
            ->name('*.txt')
            ->name('*.md')
            ->ignoreDotFiles(false)
            ->notName('*.min.*')
            ->exclude('Makefile')
            ->exclude($this->excludePaths);

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            if ($content) {
                isNotContain("\r", $content, false, 'File has \r symbol: ' . $file);
                isNotContain("\t", $content, false, 'File has \t symbol: ' . $file);
            }
        }

        isTrue(true); // One assert is a minimum for test complete
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

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->excludePaths)
            ->name('*.php')
            ->name('*.phtml');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of JS files (not minified)
     */
    public function testHeadersJS(): void
    {
        $valid = $this->prepareTemplate(implode($this->eol, $this->validHeaderJS));

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->excludePaths)
            ->name('*.js')
            ->notName('*.min.js')
            ->name('*.jsx')
            ->notName('*.min.jsx');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of CSS files (not minified)
     */
    public function testHeadersCSS(): void
    {
        $valid = $this->prepareTemplate(implode($this->eol, $this->validHeaderCSS));

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->excludePaths)
            ->name('*.css')
            ->notName('*.min.css');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of LESS files (not minified)
     */
    public function testHeadersLESS(): void
    {
        $valid = $this->prepareTemplate(implode($this->eol, $this->validHeaderLESS));

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->excludePaths)
            ->name('*.less');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of XML files
     */
    public function testHeadersXML(): void
    {
        $valid = $this->prepareTemplate(implode($this->eol, $this->validHeaderXML));

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->excludePaths)
            ->name('*.xml')
            ->name('*.xml.dist');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of INI files
     */
    public function testHeadersINI(): void
    {
        $valid = $this->prepareTemplate(implode($this->eol, $this->validHeaderINI));

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->excludePaths)
            ->name('*.ini');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of SH files
     */
    public function testHeadersSH(): void
    {
        $valid = $this->prepareTemplate(implode($this->eol, $this->validHeaderSH));

        $excludePaths = $this->excludePaths;
        $binIndex = array_search('bin', $excludePaths, true);
        if ($binIndex !== false) {
            unset($excludePaths[$binIndex]);
        }

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($excludePaths)
            ->name('*.sh');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of SQL files
     */
    public function testHeadersSQL(): void
    {
        $valid = $this->prepareTemplate(implode($this->eol, $this->validHeaderSQL));

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->excludePaths)
            ->name('*.sql');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of Makefile
     */
    public function testHeadersMakefile(): void
    {
        $valid = $this->prepareTemplate(implode($this->eol, $this->validHeaderHtaccess));

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->excludePaths)
            ->ignoreDotFiles(false)
            ->name('Makefile');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of .htaccess files
     */
    public function testHeadersHtaccess(): void
    {
        $valid = $this->prepareTemplate(implode($this->eol, $this->validHeaderHtaccess));

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->excludePaths)
            ->ignoreDotFiles(false)
            ->name('/\.htaccess/')
            ->name('htaccess.*')
            ->name('.htaccess.*');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Try to find cyrilic symbols in the code
     */
    public function testCyrillic(): void
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->excludePaths)
            ->exclude('tests')
            ->notPath(basename(__FILE__))
            ->ignoreDotFiles(false)
            ->notName('/\.md$/')
            ->notName('/\.min\.(js|css)$/')
            ->notName('/\.min\.(js|css)\.map$/');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());

            /** @noinspection NotOptimalRegularExpressionsInspection */
            if (preg_match('#[А-Яа-яЁё]#ius', $content)) {
                fail('File contains cyrilic symbols: ' . $file); // Short message in terminal
            } else {
                success();
            }
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Render copyrights
     *
     * @param string $text
     * @return string
     */
    protected function prepareTemplate($text): string
    {
        foreach ($this->replace as $const => $value) {
            $text = str_replace($const, $value, $text);
        }

        return $text;
    }
}
