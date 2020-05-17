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

.PHONY: autoload clean-build phpcov phpcpd phpcs phploc phpmd reset server server-fake-test server-phpunit test test-all update validate

SRC_PATH ?= `pwd`/src
BIN_PATH ?= `pwd`/bin

CE   = \033[0m
C_AR = \033[0;33m
C_T  = \033[0;30;46m

define title
    @echo "$(C_AR)>>> >>> >>> >>> $(C_T) $(1) $(CE)"
endef

update:
	$(call title,"Update 3rd party dependencies")
	@composer update


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
	@make clean-build
	@make validate
	@make test
	@make phpcs
	@make phpmd
	@make phpmnd
	@make phpcpd
	@make phpstan
	@make psalm
	@make phploc


validate:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Composer validate $(CE)"
	@composer diagnose
	@composer check-platform-reqs
	@composer validate --strict --no-check-all
	@php `pwd`/vendor/bin/security-checker security:check `pwd`/composer.lock


autoload:
	$(call title,"Composer autoload")
	@composer dump-autoload --optimize --no-interaction


test:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Run unit-tests $(CE)"
	@php `pwd`/vendor/phpunit/phpunit/phpunit --configuration ./phpunit.xml.dist --color --verbose --order-by=random


phpmd:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Check PHPmd $(CE)"
	@php `pwd`/vendor/phpmd/phpmd/src/bin/phpmd $(SRC_PATH),$(BIN_PATH) ansi \
        controversial,design,naming,unusedcode --verbose


phpcs:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Check Code Style $(CE)"
	@php `pwd`/vendor/squizlabs/php_codesniffer/bin/phpcs $(SRC_PATH) $(BIN_PATH)   \
        --standard=PSR12                                                            \
        --report=full                                                               \
        --colors                                                                    \
        -p -s


phpstan: ## Check PHP code by PHPStan
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Checking by PHPStan $(CE)"
	@php `pwd`/vendor/bin/phpstan analyse   \
        --level=max                         \
        --error-format=table                \
        $(SRC_PATH) $(BIN_PATH)


phpmnd: ## Check by PHP Magic Number Detector
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Checking by PHP Magic Number Detector (phpmnd) $(CE)"
	@php `pwd`/vendor/bin/phpmnd    \
        --progress                  \
        --hint                      \
        $(SRC_PATH) $(BIN_PATH)


psalm: ## Check PHP code by PHPStan
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Checking by psalm $(CE)"
	@php `pwd`/vendor/bin/psalm         \
        --config=`pwd`/psalm.xml        \
        --output-format=compact         \
        --show-info=true               \
        --show-snippet=true             \
        --find-unused-psalm-suppress    \
        --long-progress                 \
        --stats


phpcpd:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Check Copy&Paste $(CE)"
	@php `pwd`/vendor/sebastian/phpcpd/phpcpd $(SRC_PATH) $(BIN_PATH) --verbose --progress


phploc:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Show stats $(CE)"
	@php `pwd`/vendor/phploc/phploc/phploc $(SRC_PATH) $(BIN_PATH) --verbose


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
	@php `pwd`/vendor/phpunit/phpcov/phpcov merge   \
        --clover build/coverage_total/merge.xml     \
        --html   build/coverage_total/merge-html    \
        build/coverage_cov                          \
        -v


coveralls: phpcov
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Send coverage to coveralls.io $(CE)"
	@php `pwd`/vendor/satooshi/php-coveralls/bin/coveralls -vvv
