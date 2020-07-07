#
# JBZoo Toolbox - PHPUnit
#
# This file is part of the JBZoo Toolbox project.
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
# @package    PHPUnit
# @license    MIT
# @copyright  Copyright (C) JBZoo.com, All rights reserved.
# @link       https://github.com/JBZoo/PHPUnit
#

ifneq (, $(wildcard ./vendor/jbzoo/codestyle/src/init.Makefile))
    include ./vendor/jbzoo/codestyle/src/init.Makefile
endif

JBZOO_TEST_HOST   ?= "localhost"
JBZOO_TEST_PORT_1 ?= "8888"
JBZOO_TEST_PORT_2 ?= "8889"


update: ##@Project Install/Update all 3rd party dependencies
	$(call title,"Install/Update all 3rd party dependencies")
	@echo "Composer flags: $(JBZOO_COMPOSER_UPDATE_FLAGS)"
	@composer update $(JBZOO_COMPOSER_UPDATE_FLAGS)


server: ##@Project Run PHP web-server for PHPUnit tests
	@make server-fake-test
	@make server-phpunit


test-all: ##@Project Run all test
	$(call title,"Run all tests")
	@make test
	@make report-composer-graph
	@-make report-merge-coverage
	@make codestyle


server-fake-test:
	$(call title,"Start server \(Fake index\)")
	@chmod +x `pwd`/bin/phpunit-server.sh
	@sh `pwd`/bin/phpunit-server.sh                     \
        $(JBZOO_TEST_HOST)                              \
        $(JBZOO_TEST_PORT_1)                            \
        "`pwd`/tests/fixtures/http-root"                \
        "`pwd`/bin/fake-index.php"                      \
        "--index=`pwd`/tests/fixtures/http-root/index.php --cov-src=\"$(PATH_SRC)\" --cov-cov=1 --cov-xml=1 --cov-html=1"


server-phpunit:
	$(call title,"Start server \(PHPUnit\)")
	@chmod +x `pwd`/bin/phpunit-server.sh
	@sh `pwd`/bin/phpunit-server.sh                     \
        $(JBZOO_TEST_HOST)                              \
        $(JBZOO_TEST_PORT_2)                            \
        "`pwd`/tests/web-root"                          \
        "`pwd`/bin/fake-index.php"                      \
        "--index=`pwd`/tests/web-root/index.php --cov-cov=1 --cov-xml=1"
