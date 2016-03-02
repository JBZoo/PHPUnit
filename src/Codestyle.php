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

/**
 * Class Codestyle
 * @package JBZoo\PHPUnit
 * @codeCoverageIgnore
 */
abstract class Codestyle extends PHPUnit
{
    protected $_packageVendor = 'JBZoo';
    protected $_packageName = '';
    protected $_packageAuthor = '';
    protected $_packageLicense = 'MIT';

    protected $_packageDesc = array(
        'This file is part of the JBZoo CCK package.',
        'For the full copyright and license information, please view the LICENSE',
        'file that was distributed with this source code.',
    );

    protected $_le = "\n";

    protected $_replace = array();

    /**
     * Valid copyright header
     * @var array
     */
    protected static $_validHeader = array(
        '<?php',
        '/**',
        ' * _VENDOR_ _PACKAGE_',
        ' *',
        ' * _DESCRIPTION_',
        ' *',
        ' * @package   _PACKAGE_',
        ' * @license   _LICENSE_',
        ' * @copyright _COPYRIGHTS_',
        ' * @link      _LINK_',
    );

    /**
     * Ignore list for
     * @var array
     */
    protected static $_excludeFiles = array(
        '.',
        '..',
        '.idea',
        '.git',
        'build',
        'vendor',
        'resources',
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
            '_LINK_'        => 'https://github.com/JBZoo/_PACKAGE_',
            '_NAMESPACE_'   => '_VENDOR_\_PACKAGE_',
            '_COPYRIGHTS_'  => 'Copyright (C) JBZoo.com,  All rights reserved.',
            '_PACKAGE_'     => $this->_packageName,
            '_LICENSE_'     => $this->_packageLicense,
            '_AUTHOR_'      => $this->_packageAuthor,
            '_VENDOR_'      => $this->_packageVendor,
            '_DESCRIPTION_' => implode($this->_le . ' * ', $this->_packageDesc),
        );
    }

    /**
     * Render copyrights
     * @param $text
     * @return mixed
     */
    protected function _replaceCopyright($text)
    {
        foreach ($this->_replace as $const => $value) {
            $text = str_replace($const, $value, $text);
        }

        return $text;
    }

    /**
     * Test line endings
     */
    public function testFiles()
    {
        $files = getFileList(PROJECT_ROOT, '.*');

        foreach ($files as $file) {
            $content = openFile($file);

            if (!isWin()) {
                isNotContain("\r", $content, false, 'File has \r symbol: ' . $file);
            }

            isNotContain("\t", $content, false, 'File has \t symbol: ' . $file);
        }
    }

    /**
     * Test copyright headers
     *
     * @return void
     */
    public function testHeaders()
    {
        $files = getFileList(PROJECT_ROOT, '[/\\\\](src|tests)[/\\\\].*\.php$');

        foreach ($files as $file) {
            $content = openFile($file);

            // build copyrights
            $validHeader = self::$_validHeader;
            if (array_key_exists('_AUTHOR_', $this->_replace) && $this->_replace['_AUTHOR_']) {
                // $validHeader[] = ' * @author    _AUTHOR_'; // TODO add multiple authors, and it's not required
            }
            // $validHeader[] = ' */';

            $valid = $this->_replaceCopyright(implode($validHeader, $this->_le));
            isContain($valid, $content, false, 'File has no valid header: ' . $file);
        }
    }

    /**
     * Try to find cyrilic symbols in the code
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function testCyrillic()
    {
        $GLOBALS['_jbzoo_fileExcludes'][] = pathinfo(__FILE__, PATHINFO_BASENAME);

        $files = getFileList(PROJECT_SRC);

        foreach ($files as $file) {
            $content = openFile($file);
            isNotLike('#[А-Яа-яЁё]#ius', $content, 'File has no valid chars: ' . $file);
        }
    }
}
