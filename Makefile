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

.PHONY: build update test-all validate autoload test phpmd phpcs phpcpd phploc reset coveralls

SRC_PATH ?= `pwd`/src

CE   = \033[0m
C_AR = \033[0;33m
C_T  = \033[0;30;46m

update:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Update project $(CE)"
	@composer update --optimize-autoloader --no-interaction


server:
	@make server-fake-test
	@make server-phpunit


server-fake-test:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Start server (Fake index) $(CE)"
	@chmod +x ./bin/phpunit-server.sh
	@sh ./bin/phpunit-server.sh  "localhost" "8888"     \
        "`pwd`/tests/fixtures/http-root"                \
        "`pwd`/bin/fake-index.php"                      \
        "--index=`pwd`/tests/fixtures/http-root/index.php --cov-src=$(SRC_PATH) --cov-cov=1 --cov-xml=1 --cov-html=1"


server-phpunit:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Start server (PHPUnit) $(CE)"
	@chmod +x ./bin/phpunit-server.sh
	@sh ./bin/phpunit-server.sh  "localhost" "8889"     \
        "`pwd`/tests/webroot"                           \
        "`pwd`/bin/fake-index.php"                      \
        "--index=`pwd`/tests/webroot/index.php --cov-cov=1 --cov-xml=1"


test-all:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Run all tests $(CE)"
	@make clean-build validate test
	@make phpmd
	@make phpcs
	@make phpstan
	@make psalm
	@make phpcpd
	@make phploc


validate:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Composer validate $(CE)"
	@composer check-platform-reqs
	@composer validate


autoload:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Composer autoload $(CE)"
	@composer dump-autoload --optimize --no-interaction


test:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Run unit-tests $(CE)"
	@php ./vendor/phpunit/phpunit/phpunit --configuration ./phpunit.xml.dist


phpmd:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Check PHPmd $(CE)"
	@php ./vendor/phpmd/phpmd/src/bin/phpmd $(SRC_PATH) ansi \
        controversial,design,naming,unusedcode --verbose


phpcs:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Check Code Style $(CE)"
	@php ./vendor/squizlabs/php_codesniffer/bin/phpcs $(SRC_PATH) \
        --standard=PSR12                                        \
        --report=full                                           \
        --colors                                                \
        -p


phpstan: ## Check PHP code by PHPStan
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Checking by PHPStan $(CE)"
	@php `pwd`/vendor/bin/phpstan analyse   \
        --level=max                         \
        --error-format=table                \
        $(SRC_PATH)


phpmnd: ## Check by PHP Magic Number Detector
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Checking by PHP Magic Number Detector (phpmnd) $(CE)"
	@php `pwd`/vendor/bin/phpmnd $(SRC_PATH)  \
        --progress                          \
        --hint


psalm: ## Check PHP code by PHPStan
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Checking by psalm $(CE)"
	@php `pwd`/vendor/bin/psalm         \
        --config=`pwd`/psalm.xml        \
        --output-format=compact         \
        --find-unused-psalm-suppress    \
        --show-info=false


phpcpd:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Check Copy&Paste $(CE)"
	@php ./vendor/sebastian/phpcpd/phpcpd $(SRC_PATH) --verbose


phploc:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Show stats $(CE)"
	@php ./vendor/phploc/phploc/phploc $(SRC_PATH) --verbose


reset:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Hard reset $(CE)"
	@git reset --hard


clean-build:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Cleanup build directory $(CE)"
	@rm -fr ./build
	@mkdir -pv ./build
	@mkdir -pv ./build/logs


phpcov:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Merge coverage reports $(CE)"
	@mkdir -pv ./build/coverage_total
	@mkdir -pv ./build/coverage_cov
	@php ./vendor/phpunit/phpcov/phpcov merge       \
        --clover build/coverage_total/merge.xml     \
        --html   build/coverage_total/merge-html    \
        build/coverage_cov                          \
        -v


coveralls: phpcov
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Send coverage to coveralls.io $(CE)"
	@php ./vendor/satooshi/php-coveralls/bin/coveralls -vvv
