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

/**
 * Class PHPUnitAssertHtmlTest
 * @package JBZoo\PHPUnit
 */
class PHPUnitAssertHtmlTest extends PHPUnit
{
    /**
     * Test that assertHtml knows how to handle correct quoting.
     */
    public function testAssertHtmlQuotes()
    {
        $input   = '<a href="/test.html" class="active">My link</a>';
        $pattern = array(
            'a' => array('href' => '/test.html', 'class' => 'active'),
                'My link',
            '/a'
        );
        isHtml($pattern, $input);

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $input   = "<a href='/test.html' class='active'>My link</a>";
        $pattern = array(
            'a' => array('href' => '/test.html', 'class' => 'active'),
                'My link',
            '/a'
        );
        isHtml($pattern, $input);

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $input   = '<link rel="stylesheet" type="text/css" href="path/css/libs/font-awesome.css?387456735964" />';
        $pattern = array(
            'link' => array(
                'rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'preg:/.*css\/libs\/font-awesome\.css\?[0-9]+/'
            )
        );
        isHtml($pattern, $input);

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $input   = "<span><strong>Text</strong></span>";
        $pattern = array(
            '<span',
                '<strong',
                    'Text',
                '/strong',
            '/span'
        );
        isHtml($pattern, $input);

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $input   = "<span class='active'><strong>Text</strong></span>";
        $pattern = array(
            'span' => array('class'),
                '<strong',
                    'Text',
                '/strong',
            '/span'
        );
        isHtml($pattern, $input);

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $value = 220985;
        $input = '<p><strong>' . $value . '</strong></p>';
        $pattern = array(
            '<p',
                '<strong',
                    $value,
                '/strong',
            '/p'
        );
        isHtml($pattern, $input);

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $input   = '<p><strong>' . $value . '</strong></p><p><strong>' . $value . '</strong></p>';
        $pattern = array(
            '<p',
                '<strong',
                    $value,
                '/strong',
            '/p',
            '<p',
                '<strong',
                    $value,
                '/strong',
            '/p',
        );
        isHtml($pattern, $input);

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $input   = '<p><strong>' . $value . '</strong></p><p id="' . $value . '"><strong>' . $value . '</strong></p>';
        $pattern = array(
            '<p',
                '<strong',
                    $value,
                '/strong',
            '/p',
            'p' => array('id' => $value),
                '<strong',
                    $value,
                '/strong',
            '/p',
        );
        isHtml($pattern, $input);

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $input = implode('', array(
            '<link rel="stylesheet" type="text/css" href="path/styles.css" />',
            '<link rel="stylesheet" type="text/css" href="path/css/libs/font-awesome.css" />',
        ));
        $pattern = array(
            array('link' => array('rel' => 'stylesheet', 'href' => 'path/styles.css', 'type' => 'text/css')),
            array('link' => array('rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'path/css/libs/font-awesome.css')),
        );
        isHtml($pattern, $input);
    }
}
