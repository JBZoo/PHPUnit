# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

JBZoo PHPUnit is a PHP library that provides a collection of short assertion aliases and testing utilities built on top of PHPUnit. The library extends `PHPUnit\Framework\TestCase` with convenient function aliases for common test assertions.

## Development Commands

### Essential Commands
- `make update` - Install/update all dependencies via Composer
- `make test` - Run PHPUnit tests
- `make codestyle` - Run all linters and code quality checks
- `make test-all` - Run complete test suite including tests, reports, and codestyle

### Testing Commands
- `vendor/bin/phpunit` - Run PHPUnit directly
- `vendor/bin/phpunit tests/PHPUnitAliasesTest.php` - Run specific test file
- `make server-start` - Start test web servers (fake and PHPUnit servers on ports 8888/8889)
- `make server-stop` - Stop test web servers

### Code Quality
- `make codestyle` - Run linters (includes PHPStan, PHP CS Fixer, etc.)
- `make report-all` - Generate all reports including coverage

## Architecture

### Core Components

1. **Base Test Class** (`src/PHPUnit.php`)
   - Simple abstract class extending `PHPUnit\Framework\TestCase`
   - All test classes should extend `JBZoo\PHPUnit\PHPUnit`

2. **Function Libraries** (`src/functions/`)
   - `aliases.php` - Short assertion functions (isTrue, isFalse, is, etc.)
   - `defines.php` - Path definitions and autoloader setup
   - `tools.php` - Additional utility functions
   - All functions are auto-loaded via composer.json files section

3. **Test Infrastructure**
   - `tests/` - All test files following `*Test.php` naming convention
   - `tests/fixtures/` - Test fixtures and HTTP root for web server tests
   - `tests/web-root/` - Web root for PHPUnit server tests
   - `bin/` - Server scripts for HTTP testing

### Key Features

- **Short Assertion Aliases**: Functions like `isTrue()`, `isFalse()`, `is()`, `isEmpty()` instead of verbose PHPUnit methods
- **Web Server Testing**: Built-in support for testing HTTP requests with fake servers
- **Coverage Support**: Integrated code coverage reporting (HTML, XML, Clover formats)

### File Organization

```
src/
├── PHPUnit.php           # Base test class
├── functions/            # Function libraries (auto-loaded)
│   ├── aliases.php      # Assertion aliases
│   ├── defines.php      # Constants and paths
│   └── tools.php        # Utility functions
└── CovCatcher.php       # Coverage utilities
```

### Testing Patterns

Test classes should:
- Extend `JBZoo\PHPUnit\PHPUnit`
- Use short assertion aliases (e.g., `isTrue($value)` instead of `$this->assertTrue($value)`)
- Follow naming convention `*Test.php`
- Be placed in `tests/` directory

Example test structure:
```php
namespace JBZoo\PHPUnit;

class MyFeatureTest extends PHPUnit
{
    public function testSomething()
    {
        isTrue(true);
        is(1, 1);
        isEmpty([]);
    }
}
```

## Dependencies

- PHP 8.3+ required
- PHPUnit ^9.6.29 as core testing framework
- JBZoo toolbox ecosystem (codestyle, markdown, etc.)
- Development dependencies managed via jbzoo/toolbox-dev

## CI/CD

GitHub Actions workflow runs:
- PHPUnit tests across PHP 8.3, 8.4, 8.5
- Code quality checks (linters)
- Coverage reporting to Coveralls
- Matrix testing with different Composer flags (--prefer-lowest)