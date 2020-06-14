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
 * @author     Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\PHPUnit;

use JBZoo\Utils\Str;

/**
 * Class AbstractReadmeTest
 *
 * @package JBZoo\PHPUnit
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
abstract class AbstractReadmeTest extends PHPUnit
{
    // See also
    // - https://github.com/badges/shields#specification
    // - https://github.com/badges/poser

    /**
     * @var string
     */
    protected $readmeFile = 'README.md';

    /**
     * @var string
     */
    protected $codacyId = '__SEE_REPO_CONFIG__';

    /**
     * @var string
     */
    protected $vendorName = 'JBZoo';

    /**
     * @var string
     */
    protected $packageName = '__DEFINE_ME__';

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
        'travis'                  => true,
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
        'codacy',
        'latest_stable_version',
        'latest_unstable_version',
        'dependents',
        'github_issues',
        'total_downloads',
        'github_license',
    ];


    #### Test cases ####################################################################################################

    public function testTitle(): void
    {
        isContain("# {$this->vendorName} - {$this->packageName}", $this->getReadme());
    }

    public function testBadgeLine(): void
    {
        $expected = [];

        foreach ($this->badgesTemplate as $badgeName) {
            $testMethod = 'checkBadge' . str_replace('_', '', ucwords($badgeName, '_'));
            if (method_exists($this, $testMethod)) {
                $expected[] = $this->{$testMethod}();
            }
        }

        isContain(implode('    ', array_filter($expected)), $this->getReadme());
    }

    public function checkBadgeLatestStableVersion(): ?string
    {
        return $this->isContainInReadme($this->getBadgePackagist('Latest Stable Version', 'v'));
    }


    public function checkBadgeLatestUnstableVersion(): ?string
    {
        return $this->isContainInReadme($this->getBadgePackagist('Latest Unstable Version', 'v/unstable'));
    }

    public function checkBadgeTotalDownloads(): ?string
    {
        return $this->isContainInReadme($this->getBadgePackagist('Total Downloads', 'downloads', 'stats'));
    }


    public function checkBadgePackagistLicense(): ?string
    {
        return $this->isContainInReadme($this->getBadgePackagist('License', 'license'));
    }

    public function checkBadgeMonthlyDownloads(): ?string
    {
        return $this->isContainInReadme($this->getBadgePackagist('Monthly Downloads', 'd/monthly', 'stats'));
    }

    public function checkBadgeDailyDownloads(): ?string
    {
        return $this->isContainInReadme($this->getBadgePackagist('Daily Downloads', 'd/daily', 'stats'));
    }

    public function checkBadgeVersion(): ?string
    {
        return $this->isContainInReadme($this->getBadgePackagist('Version', 'version'));
    }

    public function checkBadgeComposerlock(): ?string
    {
        return $this->isContainInReadme($this->getBadgePackagist('Version', 'composerlock'));
    }

    public function checkBadgeGitattributes(): ?string
    {
        return $this->isContainInReadme($this->getBadgePackagist('.gitattributes', 'gitattributes'));
    }

    public function checkBadgeDependents(): ?string
    {
        return $this->isContainInReadme(
            $this->getBadgePackagist('Dependents', 'dependents', 'dependents?order_by=downloads')
        );
    }

    public function checkBadgeSuggesters(): ?string
    {
        return $this->isContainInReadme($this->getBadgePackagist('Suggesters', 'suggesters'));
    }

    public function checkBadgeCircleCI(): ?string
    {
        return $this->isContainInReadme($this->getBadgePackagist('CircleCI Build', 'circleci'));
    }


    ##### Other ########################################################################################################


    public function checkBadgeTravis(): ?string
    {
        return $this->isContainInReadme($this->getBadge(
            'Build Status',
            'https://travis-ci.org/__VENDOR__/__PACKAGE__.svg?branch=master',
            'https://travis-ci.org/__VENDOR__/__PACKAGE__'
        ));
    }

    public function checkBadgeCoveralls(): ?string
    {
        return $this->isContainInReadme($this->getBadge(
            'Coverage Status',
            'https://coveralls.io/repos/__VENDOR__/__PACKAGE__/badge.svg',
            'https://coveralls.io/github/__VENDOR__/__PACKAGE__?branch=master'
        ));
    }

    public function checkBadgeCodacy(): ?string
    {
        return $this->isContainInReadme($this->getBadge(
            'Codacy Badge',
            "https://app.codacy.com/project/badge/Grade/{$this->codacyId}",
            'https://www.codacy.com/gh/__VENDOR__/__PACKAGE__'
        ));
    }

    public function checkBadgePsalmCoverage(): ?string
    {
        return $this->isContainInReadme($this->getBadge(
            'Psalm Coverage',
            'https://shepherd.dev/github/__VENDOR__/__PACKAGE__/coverage.svg',
            'https://shepherd.dev/github/__VENDOR__/__PACKAGE__'
        ));
    }

    public function checkBadgeGithubIssues(): ?string
    {
        return $this->isContainInReadme($this->getBadge(
            'GitHub Issues',
            'https://img.shields.io/github/issues/__VENDOR__/__PACKAGE__',
            'https://github.com/__VENDOR__/__PACKAGE__/issues'
        ));
    }

    public function checkBadgeGithubForks(): ?string
    {
        return $this->isContainInReadme($this->getBadge(
            'GitHub Forks',
            'https://img.shields.io/github/forks/__VENDOR__/__PACKAGE__',
            'https://github.com/__VENDOR__/__PACKAGE__/network'
        ));
    }

    public function checkBadgeGithubStars(): ?string
    {
        return $this->isContainInReadme($this->getBadge(
            'GitHub Stars',
            'https://img.shields.io/github/stars/__VENDOR__/__PACKAGE__',
            'https://github.com/__VENDOR__/__PACKAGE__/stargazers'
        ));
    }

    public function checkBadgeGithubLicense(): ?string
    {
        return $this->isContainInReadme($this->getBadge(
            'GitHub License',
            'https://img.shields.io/github/license/__VENDOR__/__PACKAGE__',
            'https://github.com/__VENDOR__/__PACKAGE__/blob/master/LICENSE'
        ));
    }

    ##### Tools ########################################################################################################


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
            '__NAME__'        => $name,
            '__SVG_URL__'     => $svgUrl,
            '__SERVICE_URL__' => $linkUrl,
            '__VENDOR__'      => $this->vendorName,
            '__PACKAGE__'     => $this->packageName,
        ];

        $result = '[![__NAME__](__SVG_URL__)](__SERVICE_URL__)';
        foreach ($params as $key => $value) {
            $result = str_replace($key, $value, $result);
        }

        return (string)$result;
    }

    /**
     * @return string
     */
    protected function getReadme()
    {
        $content = (string)file_get_contents(PROJECT_ROOT . '/README.md');
        isNotEmpty($content);

        return $content;
    }

    /**
     * @param string $badge
     * @return string|null
     */
    protected function isContainInReadme(string $badge): ?string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $testCaseName = str_replace('check_badge_', '', Str::splitCamelCase($trace[1]['function']));

        $isEnabled = $this->params[$testCaseName] ?? null;
        if (null === $isEnabled) {
            return null;
        }

        if (!$isEnabled) {
            success();
            return null;
        }

        $readme = $this->getReadme();

        $isContain = strpos($readme, $badge) !== false;

        $errMessage = implode("\n", [
            "The readme file has no valid copyright in header",
            "Expected badge ({$testCaseName}):",
            str_repeat('-', 60),
            "\"{$badge}\"",
            str_repeat('-', 60)
        ]);

        isTrue($isContain, $errMessage);
        isContain($badge, $readme, false, $errMessage);

        return $badge;
    }
}
