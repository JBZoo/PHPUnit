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

use Symfony\Component\Finder\Finder;

/**
 * Class Codestyle
 * @package JBZoo\PHPUnit
 * @codeCoverageIgnore
 */
abstract class Codestyle extends PHPUnit
{
    protected $_packageName = ''; // Overload me!

    protected $_packageVendor = 'JBZoo';
    protected $_packageLicense = 'MIT';
    protected $_packageCopyright = 'Copyright (C) JBZoo.com,  All rights reserved.';
    protected $_packageLink = 'https://github.com/JBZoo/_PACKAGE_';
    protected $_packageAuthor = ''; // Deprecated!
    protected $_packageDesc = array(
        'This file is part of the JBZoo CCK package.',
        'For the full copyright and license information, please view the LICENSE',
        'file that was distributed with this source code.',
    );

    protected $_le = "\n";
    protected $_replace = array();

    /**
     * Ignore list for
     * @var array
     */
    protected $_excludePaths = array(
        '.git',
        '.idea',
        'bin',
        'bower_components',
        'build',
        'fonts',
        'logs',
        'node_modules',
        'resources',
        'vendor'
    );

    /**
     * Valid header for PHP files
     * @var array
     */
    protected $_validHeaderPHP = array(
        '<?php',
        '/**',
        ' * _VENDOR_ _PACKAGE_',
        ' *',
        ' * _DESCRIPTION_PHP_',
        ' *',
        ' * @package   _PACKAGE_',
        ' * @license   _LICENSE_',
        ' * @copyright _COPYRIGHTS_',
        ' * @link      _LINK_',
    );

    /**
     * Valid header for JavaScript files
     * @var array
     */
    protected $_validHeaderJS = array(
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
    );

    /**
     * Valid header for CSS files (if not mimified)
     * @var array
     */
    protected $_validHeaderCSS = array(
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
    );

    /**
     * Valid header for LESS files
     * @var array
     */
    protected $_validHeaderLESS = array(
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
    );

    /**
     * Valid header for XML files
     * @var array
     */
    protected $_validHeaderXML = array(
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
    );

    /**
     * Valid header for INI files
     * @var array
     */
    protected $_validHeaderINI = array(
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
    );

    /**
     * @throws \Exception
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function setUp()
    {
        parent::setUp();

        //@codeCoverageIgnoreStart
        if (!$this->_packageName) {
            throw new Exception('$this->_packageName is undefined!');
        }
        //@codeCoverageIgnoreEnd

        $this->_replace = array(
            '_LINK_'             => $this->_packageLink,
            '_NAMESPACE_'        => '_VENDOR_\_PACKAGE_',
            '_COPYRIGHTS_'       => $this->_packageCopyright,
            '_PACKAGE_'          => $this->_packageName,
            '_LICENSE_'          => $this->_packageLicense,
            '_AUTHOR_'           => $this->_packageAuthor,
            '_VENDOR_'           => $this->_packageVendor,
            '_DESCRIPTION_PHP_'  => implode($this->_le . ' * ', $this->_packageDesc),
            '_DESCRIPTION_JS_'   => implode($this->_le . ' * ', $this->_packageDesc),
            '_DESCRIPTION_CSS_'  => implode($this->_le . ' * ', $this->_packageDesc),
            '_DESCRIPTION_LESS_' => implode($this->_le . '// ', $this->_packageDesc),
            '_DESCRIPTION_XML_'  => implode($this->_le . '    ', $this->_packageDesc),
            '_DESCRIPTION_INI_'  => implode($this->_le . '; ', $this->_packageDesc),
        );
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
            ->name('*.ini')
            ->name('*.json')
            ->name('*.txt')
            ->name('*.md')
            ->notName('*.min.*')
            ->exclude($this->_excludePaths);

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isNotContain("\r", $content, false, 'File has \r symbol: ' . $file);
            isNotContain("\t", $content, false, 'File has \t symbol: ' . $file);
        }
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
            ->name('*.php');

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }
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
    }

    /**
     * Test copyright headers of XML files
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
            ->notPath(basename(__FILE__))
            ->notPath('*.md')
            ->exclude('tests');

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            isNotLike('#[А-Яа-яЁё]#ius', $content, 'File has no valid chars: ' . $file);
        }
    }

    /**
     * Render copyrights
     * @param $text
     * @return mixed
     */
    protected function _prepareTemplate($text)
    {
        foreach ($this->_replace as $const => $value) {
            $text = str_replace($const, $value, $text);
        }

        return $text;
    }
}
