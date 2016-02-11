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
 * Class PHPUnit
 * @package JBZoo\PHPUnit
 */
abstract class PHPUnit extends \PHPUnit_Framework_TestCase
{
    /**
     * Start profiler
     * @deprecated See startProfiler()
     */
    public function startProfiler()
    {
        return startProfiler();
    }

    /**
     * Simple loop profiler
     * @param int       $count
     * @param bool|true $formated
     * @return array
     * @deprecated See loopProfiler()
     */
    public function loopProfiler($count = 1, $formated = true)
    {
        return loopProfiler($count, $formated); // @codeCoverageIgnore
    }


    /**
     * Get file list in directory
     * @param       $dir
     * @param null  $filter
     * @param array $results
     * @return array
     * @deprecated See getFileList()
     */
    public function getFileList($dir, $filter = null, &$results = array())
    {
        return getFileList($dir, $filter, $results); // @codeCoverageIgnore
    }

    /**
     * Binary save to open file
     * @param $path
     * @return null|string
     * @deprecated See openFile()
     */
    public function openFile($path)
    {
        return openFile($path); // @codeCoverageIgnore
    }

    /**
     * @param string $message
     * @param null   $label
     * @deprecated See alert()
     */
    public function showAlertMessage($message, $label = null)
    {
        alert($message, $label); // @codeCoverageIgnore
    }

    /**
     * @return bool
     * @deprecated
     */
    public static function isXdebug()
    {
        return isXdebug(); // @codeCoverageIgnore
    }

    /**
     * Asserts HTML tags.
     *
     * Takes an array $expected and generates a regex from it to match the provided $string.
     * Samples for $expected:
     *
     * Checks for an input tag with a name attribute (contains any non-empty value) and an id
     * attribute that contains 'my-input':
     *
     * ```
     * ['input' => ['name', 'id' => 'my-input']]
     * ```
     *
     * Checks for two p elements with some text in them:
     *
     * ```
     * [
     *   ['p' => true],
     *   'textA',
     *   '/p',
     *   ['p' => true],
     *   'textB',
     *   '/p'
     * ]
     * ```
     *
     * You can also specify a pattern expression as part of the attribute values, or the tag
     * being defined, if you prepend the value with preg: and enclose it with slashes, like so:
     *
     * ```
     * [
     *   ['input' => ['name', 'id' => 'preg:/FieldName\d+/']],
     *   'preg:/My\s+field/'
     * ]
     * ```
     *
     * Important: This function is very forgiving about whitespace and also accepts any
     * permutation of attribute order. It will also allow whitespace between specified tags.
     *
     * @param array  $expected An array, see above
     * @param string $string   An HTML/XHTML/XML string
     * @return bool
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function assertHtml($expected, $string)
    {
        $count      = 0;
        $regex      = array();
        $normalized = $this->_normalizeHtmlExp($expected);

        foreach ($normalized as $tags) {
            $tags = $this->_tagsToString($tags);
            $count++;
            if (is_string($tags) && $tags{0} === '<') {
                $tags = array(substr($tags, 1) => array());
            } elseif (is_string($tags)) {
                $tagsTrimmed = preg_replace('/\s+/m', '', $tags);

                if (preg_match('/^\*?\//', $tags, $match) && $tagsTrimmed !== '//') {
                    $prefix = array(null, null);

                    if ($match[0] === '*/') {
                        $prefix = array('Anything, ', '.*?');
                    }
                    $regex[] = array(
                        sprintf('%sClose %s tag', $prefix[0], substr($tags, strlen($match[0]))),
                        sprintf('%s<[\s]*\/[\s]*%s[\s]*>[\n\r]*', $prefix[1], substr($tags, strlen($match[0]))),
                        $count,
                    );
                    continue;
                }

                $regex[] = $this->_getRegexByTagStr($tags, $count);
                continue;
            }
            foreach ($tags as $tag => $attributes) {
                $regex[] = array(sprintf('Open %s tag', $tag), sprintf('[\s]*<%s', preg_quote($tag, '/')), $count);

                if ($attributes === true) {
                    $attributes = array();
                }

                $count        = 1;
                $attrs        = array();
                $explanations = array();
                foreach ($attributes as $attr => $val) {
                    if (is_numeric($attr) && preg_match('/^preg\:\/(.+)\/$/i', $val, $matches)) {
                        $attrs[]        = $matches[1];
                        $explanations[] = sprintf('Regex "%s" matches', $matches[1]);
                        continue;
                    } else {
                        list($explanations, $newAttrs) = $this->_checkArrayAttrs($attr, $val, $explanations);
                        $attrs[] = $newAttrs;
                    }
                    $count++;
                }
                if ($attrs) {
                    $regex[] = array('explains' => $explanations, 'attrs' => $attrs);
                }
                $regex[] = array(sprintf('End %s tag', $tag), '[\s]*\/?[\s]*>[\n\r]*', $count);
            }
        }
        foreach ($regex as $count => $assertion) {
            $matches = false;
            if (isset($assertion['attrs'])) {
                $string = $this->_assertAttributes($assertion, $string);
                continue;
            }

            list($description, $expressions, $itemNum) = $assertion;
            foreach ((array)$expressions as $expression) {
                $expression = sprintf('/^%s/s', $expression);
                if (preg_match($expression, $string, $match)) {
                    $matches = true;
                    $string  = substr($string, strlen($match[0]));
                    break;
                }
            }
            if (!$matches) {
                //@codingStandardsIgnoreStart
                $this->assertRegExp($expression, $string, sprintf(
                    'Item #%d / regex #%d failed: %s', $itemNum, $count, $description
                ));
                //@codingStandardsIgnoreEnd
                return false;
            }
        }

        $this->assertTrue(true, '%s');
        return true;
    }

    /**
     * @param       $attr
     * @param       $val
     * @param array $explanations
     * @return string
     */
    protected function _checkArrayAttrs($attr, $val, array $explanations)
    {
        $quotes = '["\']';
        if (is_numeric($attr)) {
            $attr = $val;
            $val  = '.+?';

            $explanations[] = sprintf('Attribute "%s" present', $attr);

        } elseif (!empty($val) && preg_match('/^preg\:\/(.+)\/$/i', $val, $matches)) {
            $val    = str_replace(array('.*', '.+'), array('.*?', '.+?'), $matches[1]);
            $quotes = $val !== $matches[1] ? '["\']' : '["\']?';

            $explanations[] = sprintf('Attribute "%s" matches "%s"', $attr, $val);

        } else {
            $explanations[] = sprintf('Attribute "%s" == "%s"', $attr, $val);
            $val            = preg_quote($val, '/');
        }

        return array(
            $explanations,
            '[\s]+' . preg_quote($attr, '/') . '=' . $quotes . $val . $quotes,
        );
    }

    /**
     * Check the attributes as part of an assertTags() check.
     *
     * @param array  $assertions
     * @param string $string
     * @return string
     */
    protected function _assertAttributes(array $assertions, $string)
    {
        $asserts  = $assertions['attrs'];
        $explains = $assertions['explains'];

        do {
            $matches = false;
            foreach ($asserts as $j => $assert) {
                if (preg_match(sprintf('/^%s/s', $assert), $string, $match)) {
                    $matches = true;
                    $string  = substr($string, strlen($match[0]));
                    array_splice($asserts, $j, 1);
                    array_splice($explains, $j, 1);
                    break;
                }
            }

            if ($matches === false) {
                $this->assertTrue(false, 'Attribute did not match. Was expecting ' . $explains[$j]);
            }

            $len = count($asserts);
        } while ($len > 0);

        return $string;
    }

    /**
     * @param array $expected
     * @return array
     */
    protected function _normalizeHtmlExp(array $expected)
    {
        $normalized = array();
        foreach ((array)$expected as $key => $val) {
            if (!is_numeric($key)) {
                $normalized[] = array($key => $val);
            } else {
                $normalized[] = $val;
            }
        }

        return $normalized;
    }

    /**
     * @param $tags
     * @return string
     */
    protected function _tagsToString($tags)
    {
        if (!is_array($tags)) {
            $tags = (string)$tags;
        }

        return $tags;
    }

    /**
     * @param $tags
     * @param $count
     * @return array
     */
    protected function _getRegexByTagStr($tags, $count)
    {
        if (!empty($tags) && preg_match('/^preg\:\/(.+)\/$/i', $tags, $matches)) {
            $tags = $matches[1];
            $type = 'Regex matches';
        } else {
            $tags = preg_quote($tags, '/');
            $type = 'Text equals';
        }

        return array(sprintf('%s "%s"', $type, $tags), $tags, $count);
    }
}
