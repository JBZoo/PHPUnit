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
        '__BR__',
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

        isContain(trim(implode('', array_filter($expectedBadges))), $this->getReadme());

        $readme = $this->getReadme();
        foreach ($expectedBadges as $badgeName => $expectedBadge) {
            $expectedBadge = trim($expectedBadge);
            if ($expectedBadge) {
                $isContain = strpos($readme, $expectedBadge) !== false;

                $errMessage = implode("\n", [
                    "The readme file has no valid copyright in header",
                    "Expected badge ({$badgeName}):",
                    str_repeat('-', 60),
                    $expectedBadge,
                    str_repeat('-', 60)
                ]);

                isTrue($isContain, $errMessage);
                isContain($expectedBadge, $readme, false, $errMessage);
            }
        }
    }

    public function checkBadgeLatestStableVersion(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Latest Stable Version', 'v'));
    }


    public function checkBadgeLatestUnstableVersion(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Latest Unstable Version', 'v/unstable'));
    }

    public function checkBadgeTotalDownloads(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Total Downloads', 'downloads', 'stats'));
    }


    public function checkBadgePackagistLicense(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('License', 'license'));
    }

    public function checkBadgeMonthlyDownloads(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Monthly Downloads', 'd/monthly', 'stats'));
    }

    public function checkBadgeDailyDownloads(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Daily Downloads', 'd/daily', 'stats'));
    }

    public function checkBadgeVersion(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Version', 'version'));
    }

    public function checkBadgeComposerlock(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Version', 'composerlock'));
    }

    public function checkBadgeGitattributes(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('.gitattributes', 'gitattributes'));
    }

    public function checkBadgeDependents(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadgePackagist('Dependents', 'dependents', 'dependents?order_by=downloads')
        );
    }

    public function checkBadgeSuggesters(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Suggesters', 'suggesters'));
    }

    public function checkBadgeCircleCI(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('CircleCI Build', 'circleci'));
    }


    ##### Other ########################################################################################################


    public function checkBadgeTravis(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'Build Status',
            'https://travis-ci.org/__VENDOR__/__PACKAGE__.svg?branch=master',
            'https://travis-ci.org/__VENDOR__/__PACKAGE__'
        ));
    }

    public function checkBadgeCoveralls(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'Coverage Status',
            'https://coveralls.io/repos/__VENDOR__/__PACKAGE__/badge.svg',
            'https://coveralls.io/github/__VENDOR__/__PACKAGE__?branch=master'
        ));
    }

    public function checkBadgeCodacy(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'Codacy Badge',
            "https://app.codacy.com/project/badge/Grade/{$this->codacyId}",
            'https://www.codacy.com/gh/__VENDOR__/__PACKAGE__'
        ));
    }

    public function checkBadgePsalmCoverage(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'Psalm Coverage',
            'https://shepherd.dev/github/__VENDOR__/__PACKAGE__/coverage.svg',
            'https://shepherd.dev/github/__VENDOR__/__PACKAGE__'
        ));
    }

    public function checkBadgeGithubIssues(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'GitHub Issues',
            'https://img.shields.io/github/issues/__VENDOR__/__PACKAGE__',
            'https://github.com/__VENDOR__/__PACKAGE__/issues'
        ));
    }

    public function checkBadgeGithubForks(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'GitHub Forks',
            'https://img.shields.io/github/forks/__VENDOR__/__PACKAGE__',
            'https://github.com/__VENDOR__/__PACKAGE__/network'
        ));
    }

    public function checkBadgeGithubStars(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'GitHub Stars',
            'https://img.shields.io/github/stars/__VENDOR__/__PACKAGE__',
            'https://github.com/__VENDOR__/__PACKAGE__/stargazers'
        ));
    }

    public function checkBadgeGithubLicense(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
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
    protected function getPreparedBadge(string $badge): ?string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $testCaseName = str_replace('check_badge_', '', $this->splitCamelCase($trace[1]['function']));

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
    protected function splitCamelCase(string $input): string
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
