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

use Symfony\Component\Finder\Finder;

/**
 * Class Codestyle
 *
 * @package JBZoo\PHPUnit
 * @codeCoverageIgnore
 */
abstract class Codestyle extends PHPUnit
{
    protected $_packageName = ''; // Overload me!

    protected $_packageVendor    = 'JBZoo';
    protected $_packageLicense   = 'MIT';
    protected $_packageCopyright = 'Copyright (C) JBZoo.com, All rights reserved.';
    protected $_packageLink      = 'https://github.com/JBZoo/_PACKAGE_';
    protected $_packageAuthor    = '';
    protected $_packageDesc      = [
        'This file is part of the JBZoo CCK package.',
        'For the full copyright and license information, please view the LICENSE',
        'file that was distributed with this source code.',
    ];

    protected $_le      = "\n";
    protected $_replace = [];

    /**
     * Ignore list for
     *
     * @var array
     */
    protected $_excludePaths = [
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
    protected $_validHeaderPHP = [
        '<?php',
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
    protected $_validHeaderJS = [
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
    protected $_validHeaderCSS = [
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
    protected $_validHeaderLESS = [
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
    protected $_validHeaderXML = [
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
    protected $_validHeaderINI = [
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
    protected $_validHeaderSH = [
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
    protected $_validHeaderSQL = [
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
    protected $_validHeaderHtaccess = [
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
    public function setUp()
    {
        parent::setUp();

        if (!$this->_packageName) {
            throw new Exception('$this->_packageName is undefined!');
        }

        if (!class_exists(Finder::class)) {
            throw new Exception('symfony/finder requreid for CodeStyle unit tests');
        }

        $this->_replace = [
            '_LINK_'                 => $this->_packageLink,
            '_NAMESPACE_'            => '_VENDOR_\_PACKAGE_',
            '_COPYRIGHTS_'           => $this->_packageCopyright,
            '_PACKAGE_'              => $this->_packageName,
            '_LICENSE_'              => $this->_packageLicense,
            '_AUTHOR_'               => $this->_packageAuthor,
            '_VENDOR_'               => $this->_packageVendor,
            '_DESCRIPTION_PHP_'      => implode($this->_le . ' * ', $this->_packageDesc),
            '_DESCRIPTION_JS_'       => implode($this->_le . ' * ', $this->_packageDesc),
            '_DESCRIPTION_CSS_'      => implode($this->_le . ' * ', $this->_packageDesc),
            '_DESCRIPTION_LESS_'     => implode($this->_le . '// ', $this->_packageDesc),
            '_DESCRIPTION_XML_'      => implode($this->_le . '    ', $this->_packageDesc),
            '_DESCRIPTION_INI_'      => implode($this->_le . '; ', $this->_packageDesc),
            '_DESCRIPTION_SH_'       => implode($this->_le . '# ', $this->_packageDesc),
            '_DESCRIPTION_SQL_'      => implode($this->_le . '-- ', $this->_packageDesc),
            '_DESCRIPTION_HTACCESS_' => implode($this->_le . '# ', $this->_packageDesc),
        ];
    }

    /**
     * Test line endings
     */
    public function testFiles()
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
            ->exclude($this->_excludePaths);

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            if (strlen($content) > 0) {
                isNotContain("\r", $content, false, 'File has \r symbol: ' . $file);
                isNotContain("\t", $content, false, 'File has \t symbol: ' . $file);
            }
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of PHP files
     */
    public function testHeadersPHP()
    {
        $valid = $this->_prepareTemplate(implode($this->_validHeaderPHP, $this->_le));

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->_excludePaths)
            ->name('*.php')
            ->name('*.phtml');

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of JS files (not minified)
     */
    public function testHeadersJS()
    {
        $valid = $this->_prepareTemplate(implode($this->_validHeaderJS, $this->_le));

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->_excludePaths)
            ->name('*.js')
            ->notName('*.min.js')
            ->name('*.jsx')
            ->notName('*.min.jsx');

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of CSS files (not minified)
     */
    public function testHeadersCSS()
    {
        $valid = $this->_prepareTemplate(implode($this->_validHeaderCSS, $this->_le));

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->_excludePaths)
            ->name('*.css')
            ->notName('*.min.css');

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of LESS files (not minified)
     */
    public function testHeadersLESS()
    {
        $valid = $this->_prepareTemplate(implode($this->_validHeaderLESS, $this->_le));

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->_excludePaths)
            ->name('*.less');

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of XML files
     */
    public function testHeadersXML()
    {
        $valid = $this->_prepareTemplate(implode($this->_validHeaderXML, $this->_le));

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->_excludePaths)
            ->name('*.xml')
            ->name('*.xml.dist');

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of INI files
     */
    public function testHeadersINI()
    {
        $valid = $this->_prepareTemplate(implode($this->_validHeaderINI, $this->_le));

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->_excludePaths)
            ->name('*.ini');

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of SH files
     */
    public function testHeadersSH()
    {
        $valid = $this->_prepareTemplate(implode($this->_validHeaderSH, $this->_le));

        $excludePaths = $this->_excludePaths;
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

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of SQL files
     */
    public function testHeadersSQL()
    {
        $valid = $this->_prepareTemplate(implode($this->_validHeaderSQL, $this->_le));

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->_excludePaths)
            ->name('*.sql');

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of Makefile
     */
    public function testHeadersMakefile()
    {
        $valid = $this->_prepareTemplate(implode($this->_validHeaderHtaccess, $this->_le));

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->_excludePaths)
            ->ignoreDotFiles(false)
            ->name('Makefile');

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Test copyright headers of .htaccess files
     */
    public function testHeadersHtaccess()
    {
        $valid = $this->_prepareTemplate(implode($this->_validHeaderHtaccess, $this->_le));

        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->_excludePaths)
            ->ignoreDotFiles(false)
            ->name('/\.htaccess/')
            ->name('htaccess.*')
            ->name('.htaccess.*');

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    /**
     * Try to find cyrilic symbols in the code
     */
    public function testCyrillic()
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->_excludePaths)
            ->exclude('tests')
            ->notPath(basename(__FILE__))
            ->ignoreDotFiles(false)
            ->notName('/\.md$/')
            ->notName('/\.min\.(js|css)$/')
            ->notName('/\.min\.(js|css)\.map$/');

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());

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
    protected function _prepareTemplate($text): string
    {
        foreach ($this->_replace as $const => $value) {
            $text = str_replace($const, $value, $text);
        }

        return $text;
    }
}
