#!/usr/bin/env sh

#
# JBZoo PHPUnit
#
# This file is part of the JBZoo CCK package.
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
# @package    PHPUnit
# @license    MIT
# @copyright  Copyright (C) JBZoo.com, All rights reserved.
# @link       https://github.com/JBZoo/PHPUnit
#

if [ "$1" != "" ]; then HTTP_HOST=$1; else  HTTP_HOST="127.0.0.1";      fi
if [ "$2" != "" ]; then HTTP_PORT=$2; else  HTTP_PORT="8081";           fi
if [ "$3" != "" ]; then HTTP_ROOT=$3; else  HTTP_ROOT=".";              fi
if [ "$4" != "" ]; then HTTP_FILE=$4; else  HTTP_FILE="./index.php";    fi
if [ "$5" != "" ]; then ARGUMENTS=$5; else  ARGUMENTS="";               fi

echo "Host: $HTTP_HOST:$HTTP_PORT";
echo "Root: $HTTP_ROOT";
echo "File: $HTTP_FILE";
echo "Args: $ARGUMENTS";
echo "";

PHPUNINT_ARGUMENTS="$ARGUMENTS" php -S "$HTTP_HOST:$HTTP_PORT" -t "$HTTP_ROOT" "$HTTP_FILE" &
