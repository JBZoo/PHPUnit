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
 */

namespace JBZoo\PHPUnit\Benchmark;

/**
 * Class Util
 * @package JBZoo\PHPUnit\Benchmark
 */
class Util
{
    /**
     * Converts 1024 B to 1K, etc.
     *
     * @param  int     $number    i.e.: 1280
     * @param  integer $precision i.e.: 1.25 for precision = 2
     * @return string  i.e.: 1.25 KB
     */
    public static function memFormat($number, $precision = 2)
    {
        if ($number < 0) {
            $number = 0;
        }

        $sizes = array(
            '0' => 'B',
            '1' => 'KB',
            '2' => 'MB',
            '3' => 'GB',
            '4' => 'TB',
        );

        $scale = $number === 0 ? 0 : floor(log($number, 1024));

        return round($number / pow(1024, $scale), $precision) . ' ' . $sizes[$scale];
    }

    /**
     * @param int $time
     * @return string
     */
    public static function timeFormat($time)
    {
        return number_format($time * 1000, 0, '.', ' ') . ' ms';
    }

    /**
     * @param $min
     * @param $value
     * @return string
     */
    public static function relativePerc($min, $value)
    {
        $min   = (float)$min;
        $value = (float)$value;

        if (!$min || $min === $value) {
            return '100';

        } else {
            $min     = abs($min);
            $percent = round($value / $min * 100);

            return number_format($percent, 0, '.', ' ');
        }
    }
}
