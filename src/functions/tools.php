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

use JBZoo\HttpClient\HttpClient;
use JBZoo\HttpClient\Response;
use JBZoo\Profiler\Benchmark;
use JBZoo\Utils\Cli;
use Symfony\Component\VarDumper\VarDumper;

// @codingStandardsIgnoreFile
global $_jbzoo_profiler, $_jbzoo_fileExcludes; // Yes, this is not cool stuff...

$_jbzoo_profiler     = array();
$_jbzoo_fileExcludes = array(
    '.',
    '..',
    '.git',
    '.idea',
    'logs',
    'bin',
    'build',
    'vendor',
    'resources',
);

/**
 * Check is current OS Windows
 * @return bool
 */
function isWin()
{
    return strncasecmp(PHP_OS, 'WIN', 3) === 0;
}

/**
 * Useful console dump
 * @param mixed  $var
 * @param bool   $isDie
 * @param string $label
 *
 * @SuppressWarnings(PHPMD.ExitExpression)
 */
function dump($var, $isDie = true, $label = '')
{
    // get trace mesage
    $trace     = debug_backtrace(false);
    $dirname   = pathinfo(dirname($trace[0]['file']), PATHINFO_BASENAME);
    $filename  = pathinfo($trace[0]['file'], PATHINFO_BASENAME);
    $line      = $trace[0]['line'];
    $callplace = "({$dirname}/{$filename}:{$line})";

    // output backtrace information
    $message = ($label ? '--- "' . $label . '" ---' : str_repeat('-', 20));
    $message = PHP_EOL . $message . ' ' . $callplace;

    fwrite(STDOUT, $message . PHP_EOL);

    $isSimpleVar = is_string($var) || is_numeric($var) || is_bool($var) || null === $var;

    if ($isSimpleVar) {
        ob_start();
        var_dump($var);
        $dump = ob_get_contents();
        ob_end_clean();

        fwrite(STDOUT, $dump);

    } else {
        VarDumper::dump($var);
    }

    if ($isDie) {
        //@codeCoverageIgnoreStart
        fwrite(STDOUT, 'Dump die!' . PHP_EOL);
        exit(255);
        //@codeCoverageIgnoreEnd
    }
}

/**
 * @param string $message
 * @param bool   $addEol
 */
function cliMessage($message, $addEol = true)
{
    $message = (string)$message;
    if ($addEol) {
        $message .= PHP_EOL;
    }

    MessageBuffer::getInstance()->info($message);
}

/**
 * @param string $message
 * @param bool   $addEol
 * @codeCoverageIgnore
 */
function cliError($message, $addEol = true)
{
    $message = (string)$message;
    if ($addEol) {
        $message .= PHP_EOL;
    }

    MessageBuffer::getInstance()->error($message);
}

/**
 * Show alert message
 * @param string $message
 * @param null   $label
 * @deprecated
 */
function alert($message, $label = null)
{
    if (!is_string($message)) {
        $message = print_r($message, true);
    }

    $message = PHP_EOL . ($label ? $label . ': ' : '') . $message;

    cliError($message);
}

/**
 * @return bool
 */
function isXdebug()
{
    return extension_loaded('xdebug');
}

/**
 * Start profiler
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
function startProfiler()
{
    // cleanup and set first mark
    $GLOBALS['_jbzoo_profiler'] = array(
        'times'    => array(microtime(true)),
        'memories' => array(memory_get_usage(false)),
    );
}

/**
 * Simple loop profiler
 * @param int       $count
 * @param bool|true $formated
 * @return array
 */
function loopProfiler($count = 1, $formated = true)
{
    global $_jbzoo_profiler;

    $time   = microtime(true);
    $memory = memory_get_usage(false);

    $_timeDiff   = $time - end($_jbzoo_profiler['times']);
    $_memoryDiff = $memory - end($_jbzoo_profiler['memories']);

    $_jbzoo_profiler['times'][]    = $time;
    $_jbzoo_profiler['memories'][] = $memory;

    // build report
    $count = (int)abs($count);
    if ($formated) {
        $timeDiff = number_format($_timeDiff * 1000, 2, '.', ' ') . ' ms';
        $timeOne  = number_format($_timeDiff * 1000 / $count, 2, '.', ' ') . ' ms';
        $memoDiff = number_format($_memoryDiff / 1024, 2, '.', ' ') . ' KB';
        $memoOne  = number_format($_memoryDiff / 1024 / $count, 2, '.', ' ') . ' KB';
        $count    = number_format($count, 0, '', ' ');

        $result = implode(';   ', array(
            'TIME: ' . $timeDiff . '/' . $timeOne,
            'MEMO: ' . $memoDiff . '/' . $memoOne,
            'COUNT: ' . $count,
        ));

    } else {
        $result = array(
            'time-diff' => $_timeDiff,
            'time-one'  => $_timeDiff / $count,
            'memo-diff' => $_memoryDiff,
            'memo-one'  => $_memoryDiff / $count,
            'count'     => $count,
        );
    }

    return $result;
}


/**
 * Get file list in directory
 * @param       $dir
 * @param null  $filter
 * @param array $results
 * @return array
 *
 * @deprecated use symfony/finder
 */
function getFileList($dir, $filter = null, &$results = array())
{
    global $_jbzoo_fileExcludes;

    $files = scandir($dir);
    foreach ($files as $value) {
        $path = $dir . DIRECTORY_SEPARATOR . $value;

        $path = realpath($path);

        if (!in_array($value, $_jbzoo_fileExcludes, true)) {
            if (is_dir($path)) {
                return getFileList($path, $filter, $results);

            } else {
                if ($filter) {

                    $regexp = '#' . $filter . '#u';
                    if (preg_match($regexp, $path)) {
                        $results[] = $path;
                    }

                } else {
                    $results[] = $path;
                }
            }
        }
    }

    return $results;
}

/**
 * Binary save to open file
 * @param $path
 * @return null|string
 */
function openFile($path)
{
    $contents = null;

    if ($realPath = realpath($path)) {
        $filesize = filesize($realPath);

        if ($filesize > 0) {
            $handle   = fopen($realPath, 'rb');
            $contents = fread($handle, $filesize);
            fclose($handle);
        }
    }

    return $contents;
}

/**
 * @link http://www.php.net/manual/en/control-structures.declare.php#control-structures.declare.ticks
 *
 * @param array $tests
 * @param array $options
 * @return array
 *
 * @deprecated
 */
function runBench(array $tests, array $options = array())
{
    return Benchmark::compare($tests, $options);
}

/**
 * @param string $command
 * @param array  $args
 * @param null   $cwd
 * @param bool   $verbose
 * @return string
 */
function cmd($command, $args = array(), $cwd = null, $verbose = false)
{
    return Cli::exec($command, $args, $cwd, $verbose);
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
function isHtml($expected, $string)
{
    $count      = 0;
    $regex      = array();
    $normalized = _normalizeHtmlExp($expected);

    foreach ($normalized as $tags) {
        $tags = _tagsToString($tags);
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

            $regex[] = _getRegexByTagStr($tags, $count);
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
                    list($explanations, $newAttrs) = _checkArrayAttrs($attr, $val, $explanations);
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
            $string = _assertAttributes($assertion, $string);
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
            assertRegExp($expression, $string, sprintf(
                'Item #%d / regex #%d failed: %s', $itemNum, $count, $description
            ));
            //@codingStandardsIgnoreEnd
            return false;
        }
    }

    isTrue(true, '%s');
    return true;
}

/**
 * @param       $attr
 * @param       $val
 * @param array $explanations
 * @return string
 */
function _checkArrayAttrs($attr, $val, array $explanations)
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
function _assertAttributes(array $assertions, $string)
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
            isTrue(false, 'Attribute did not match. Was expecting ' . $explains[$j]);
        }

        $len = count($asserts);
    } while ($len > 0);

    return $string;
}

/**
 * @param array $expected
 * @return array
 */
function _normalizeHtmlExp(array $expected)
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
function _tagsToString($tags)
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
function _getRegexByTagStr($tags, $count)
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

/**
 * @param string       $url
 * @param string|array $args
 * @param string       $method
 * @param array        $options
 * @return Response
 */
function httpRequest($url, $args = null, $method = 'GET', array $options = array())
{
    $httClient = new HttpClient();
    return $httClient->request($url, $args, $method, $options);
}
