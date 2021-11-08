<?php

/**
 * JBZoo Toolbox - PHPUnit
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    PHPUnit
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/PHPUnit
 */

declare(strict_types=1);

namespace JBZoo\PHPUnit;

/**
 * Class AbstractReadmeTest
 *
 * @package JBZoo\PHPUnit
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @codeCoverageIgnore
 */
abstract class AbstractReadmeTest extends PHPUnit
{
    // See also
    // - https://github.com/badges/shields#specification
    // - https://github.com/badges/poser

    /**
     * @var string
     */
    protected $vendorName = 'JBZoo';

    /**
     * @var string
     */
    protected $packageName = '__DEFINE_ME__';

    /**
     * @var string
     */
    protected $readmeFile = 'README.md';

    /**
     * @var bool[]
     */
    protected $params = [
        'latest_stable_version'   => true,
        'latest_unstable_version' => true,
        'version'                 => false,
        'total_downloads'         => true,
        'dependents'              => true,
        'suggesters'              => false,
        'daily_downloads'         => false,
        'monthly_downloads'       => false,
        'composerlock'            => false,
        'gitattributes'           => false,
        'packagist_license'       => false,
        'github_issues'           => true,
        'github_license'          => true,
        'github_forks'            => false,
        'github_stars'            => false,
        'codacy'                  => true,
        'psalm_coverage'          => true,
        'docker_build'            => false,
        'docker_pulls'            => false,
        'scrutinizer'             => false,
        'codefactor'              => false,
        'sonarcloud'              => false,
        'strict_types'            => false,
        'travis'                  => false,
        'coveralls'               => true,
        'circle_ci'               => false,
    ];

    /**
     * @var string[]
     */
    protected $badgesTemplate = [
        'travis',
        'coveralls',
        'psalm_coverage',
        'scrutinizer',
        'codefactor',
        'sonarcloud',
        'strict_types',
        '__BR__',
        'latest_stable_version',
        'latest_unstable_version',
        'dependents',
        'github_issues',
        'total_downloads',
        'github_license',
        'docker_build',
        'docker_pulls',
    ];

    /**
     * @var string
     */
    protected $codacyId = '__SEE_REPO_CONFIG__';


    #### Test cases ####################################################################################################

    public function testReadmeHeader(): void
    {
        $expectedBadges = [];

        foreach ($this->badgesTemplate as $badgeName) {
            if ($badgeName === '__BR__') {
                $expectedBadges[$badgeName] = "\n";
            } else {
                $testMethod = 'checkBadge' . str_replace('_', '', ucwords($badgeName, '_'));
                if (method_exists($this, $testMethod)) {
                    if ($tmpBadge = $this->{$testMethod}()) {
                        $expectedBadges[$badgeName] = "{$tmpBadge}    ";
                    }
                } else {
                    fail("Method not found: '{$testMethod}'");
                }
            }
        }

        $expectedBadgeLine = implode("\n", [
            $this->getTitle(),
            '',
            trim(implode('', array_filter($expectedBadges))),
            '',
            ''
        ]);

        isFileContains($expectedBadgeLine, PROJECT_ROOT . '/README.md');
    }

    #### Tools #########################################################################################################

    /**
     * @return string
     */
    protected function getTitle(): string
    {
        return "# {$this->vendorName} / {$this->packageName}";
    }

    /**
     * @return string|null
     */
    protected function checkBadgeLatestStableVersion(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Stable Version', 'version'));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeLatestUnstableVersion(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Latest Unstable Version', 'v/unstable'));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeTotalDownloads(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Total Downloads', 'downloads', 'stats'));
    }

    /**
     * @return string|null
     */
    protected function checkBadgePackagistLicense(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('License', 'license'));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeMonthlyDownloads(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Monthly Downloads', 'd/monthly', 'stats'));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeDailyDownloads(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Daily Downloads', 'd/daily', 'stats'));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeVersion(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Version', 'version'));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeComposerlock(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Version', 'composerlock'));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeGitattributes(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('.gitattributes', 'gitattributes'));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeDependents(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadgePackagist('Dependents', 'dependents', 'dependents?order_by=downloads')
        );
    }

    /**
     * @return string|null
     */
    protected function checkBadgeSuggesters(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Suggesters', 'suggesters'));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeCircleCI(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('CircleCI Build', 'circleci'));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeTravis(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'Build Status',
            'https://travis-ci.org/__VENDOR_ORIG__/__PACKAGE_ORIG__.svg',
            'https://travis-ci.org/__VENDOR_ORIG__/__PACKAGE_ORIG__'
        ));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeCoveralls(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'Coverage Status',
            'https://coveralls.io/repos/__VENDOR_ORIG__/__PACKAGE_ORIG__/badge.svg',
            'https://coveralls.io/github/__VENDOR_ORIG__/__PACKAGE_ORIG__'
        ));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeCodacy(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'Codacy Badge',
            "https://app.codacy.com/project/badge/Grade/{$this->codacyId}",
            'https://www.codacy.com/gh/__VENDOR__/__PACKAGE__'
        ));
    }

    /**
     * @return string|null
     */
    protected function checkBadgePsalmCoverage(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'Psalm Coverage',
            'https://shepherd.dev/github/__VENDOR_ORIG__/__PACKAGE_ORIG__/coverage.svg',
            'https://shepherd.dev/github/__VENDOR_ORIG__/__PACKAGE_ORIG__'
        ));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeGithubIssues(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'GitHub Issues',
            'https://img.shields.io/github/issues/__VENDOR__/__PACKAGE__',
            'https://github.com/__VENDOR_ORIG__/__PACKAGE_ORIG__/issues'
        ));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeGithubForks(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'GitHub Forks',
            'https://img.shields.io/github/forks/__VENDOR__/__PACKAGE__',
            'https://github.com/__VENDOR_ORIG__/__PACKAGE_ORIG__/network'
        ));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeGithubStars(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'GitHub Stars',
            'https://img.shields.io/github/stars/__VENDOR__/__PACKAGE__',
            'https://github.com/__VENDOR_ORIG__/__PACKAGE_ORIG__/stargazers'
        ));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeGithubLicense(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'GitHub License',
            'https://img.shields.io/github/license/__VENDOR__/__PACKAGE__',
            'https://github.com/__VENDOR_ORIG__/__PACKAGE_ORIG__/blob/master/LICENSE'
        ));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeDockerBuild(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'Docker Cloud Build Status',
            'https://img.shields.io/docker/cloud/build/__VENDOR__/__PACKAGE__.svg',
            'https://hub.docker.com/r/__VENDOR__/__PACKAGE__'
        ));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeDockerPulls(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'Docker Pulls',
            'https://img.shields.io/docker/pulls/__VENDOR__/__PACKAGE__.svg',
            'https://hub.docker.com/r/__VENDOR__/__PACKAGE__'
        ));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeScrutinizer(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'Scrutinizer Code Quality',
            'https://scrutinizer-ci.com/g/__VENDOR__/__PACKAGE__/badges/quality-score.png?b=master',
            'https://scrutinizer-ci.com/g/__VENDOR__/__PACKAGE__/?branch=master'
        ));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeCodefactor(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'CodeFactor',
            'https://www.codefactor.io/repository/github/__VENDOR__/__PACKAGE__/badge',
            'https://www.codefactor.io/repository/github/__VENDOR__/__PACKAGE__/issues'
        ));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeSonarcloud(): ?string
    {
        $project = '__VENDOR_ORIG_____PACKAGE_ORIG__';

        return $this->getPreparedBadge($this->getBadge(
            'Quality Gate Status',
            "https://sonarcloud.io/api/project_badges/measure?project={$project}&metric=alert_status",
            "https://sonarcloud.io/dashboard?id={$project}"
        ));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeStrictTypes(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'PHP Strict Types',
            'https://img.shields.io/badge/strict__types-%3D1-brightgreen',
            'https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.strict'
        ));
    }

    /**
     * @param string      $name
     * @param string      $mode
     * @param string|null $postfix
     * @return string
     */
    protected function getBadgePackagist(string $name, string $mode, ?string $postfix = null): string
    {
        return $this->getBadge(
            $name,
            "https://poser.pugx.org/__VENDOR__/__PACKAGE__/{$mode}",
            'https://packagist.org/packages/__VENDOR__/__PACKAGE__' . ($postfix ? "/{$postfix}" : '')
        );
    }

    /**
     * @param string $name
     * @param string $svgUrl
     * @param string $linkUrl
     * @return string
     */
    protected function getBadge(string $name, string $svgUrl, string $linkUrl = ''): string
    {
        /** @var string[] $params */
        $params = [
            '__NAME__'         => $name,
            '__SVG_URL__'      => $svgUrl,
            '__SERVICE_URL__'  => $linkUrl,
            '__VENDOR_ORIG__'  => $this->vendorName,
            '__PACKAGE_ORIG__' => $this->packageName,
            '__VENDOR__'       => strtolower($this->vendorName),
            '__PACKAGE__'      => strtolower($this->packageName),
        ];

        $result = '[![__NAME__](__SVG_URL__)](__SERVICE_URL__)';
        foreach ($params as $key => $value) {
            $result = str_replace($key, $value, $result);
        }

        return $result;
    }

    /**
     * @return string
     */
    protected static function getReadme(): string
    {
        $content = (string)file_get_contents(PROJECT_ROOT . '/README.md');
        isNotEmpty($content);

        return $content;
    }

    /**
     * @param string $badge
     * @return string|null
     */
    protected function getPreparedBadge(string $badge): ?string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $testCaseName = str_replace('check_badge_', '', self::splitCamelCase($trace[1]['function']));

        $isEnabled = $this->params[$testCaseName] ?? null;
        if (null === $isEnabled) {
            return null;
        }

        if (!$isEnabled) {
            success();
            return null;
        }

        return $badge;
    }

    /**
     * @param string $input
     * @return string
     */
    protected static function splitCamelCase(string $input): string
    {
        $original = $input;

        $output = (string)preg_replace(['/(?<=[^A-Z])([A-Z])/', '/(?<=[^0-9])([0-9])/'], '_$0', $input);
        $output = (string)preg_replace('#_{1,}#', '_', $output);

        $output = trim($output);
        $output = strtolower($output);

        if ('' === $output) {
            return $original;
        }

        return $output;
    }
}
