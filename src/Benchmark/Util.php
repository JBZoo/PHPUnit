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

namespace JBZoo\PHPUnit\Benchmark;

/**
 * Class Util
 * @package JBZoo\PHPUnit\Benchmark
 */
class Util
{
    /**
     * @param float $number
     * @param int   $significant
     * @return float
     */
    public static function round($number, $significant = 0)
    {
        $order = floor(log($number) / log(10));

        return @round($number / pow(10, $order), $significant) * pow(10, $order);
    }

    /**
     * Converts 1024 to 1K, etc.
     *
     * @param  double  $number    i.e.: 1280
     * @param  integer $precision i.e.: 1.25 for precision = 2
     * @param  string  $unit      suffix of the unit, may be empty
     * @param  integer $factor    change base to 1000 or 1024
     * @return string  i.e.: 1.25 kB
     */
    public static function convertToSI($number, $precision = 2, $unit = 'B', $factor = 1024)
    {
        if ($number < 0) {
            $number = 0;
        }

        static $sizes = array(
            '-3' => 'n',
            '-2' => 'µ',
            '-1' => 'm',
            '0'  => '',
            '1'  => 'k',
            '2'  => 'M',
            '3'  => 'G',
            '4'  => 'T',
        );

        $scale = $number == 0 ? 0 : floor(log($number, $factor));

        return round($number / pow($factor, $scale), $precision) . ' ' . $sizes[$scale] . $unit;
    }

    /**
     * @param $min
     * @param $value
     * @return string
     */
    public static function relativePerc($min, $value)
    {
        if ($min == 0 || $min == $value) {
            return '~';

        } else {
            $min = abs($min);

            $percent = round(($value - $min) / $min * 100);

            return number_format($percent, 0, '.', ' ');
        }
    }
}
