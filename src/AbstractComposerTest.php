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

use function JBZoo\Data\json;

/**
 * Class AbstractComposerTest
 *
 * @package JBZoo\PHPUnit
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
abstract class AbstractComposerTest extends PHPUnit
{
    /**
     * @var string
     */
    protected $authorName = 'Denis Smetannikov';

    /**
     * @var string
     */
    protected $authorEmail = 'admin@jbzoo.com';

    /**
     * @var string
     */
    protected $authorRole = 'lead';

    /**
     * @var string
     */
    protected $devBranch = 'dev-master';

    /**
     * @var string
     */
    protected $phpVersion = '^7.2';

    public function testAuthor(): void
    {
        $composerPath = PROJECT_ROOT . '/composer.json';
        $composerJson = json($composerPath);

        if ($this->authorName) {
            isSame($this->authorName, $composerJson->find('authors.0.name'), "See file: {$composerPath}");
        }

        if ($this->authorEmail) {
            isSame($this->authorEmail, $composerJson->find('authors.0.email'), "See file: {$composerPath}");
        }

        if ($this->authorRole) {
            isSame($this->authorRole, $composerJson->find('authors.0.role'), "See file: {$composerPath}");
        }
    }

    public function testDevMasterAlias(): void
    {
        $composerPath = PROJECT_ROOT . '/composer.json';
        $composerJson = json($composerPath);

        isNotEmpty($composerJson->find("extra.branch-alias.{$this->devBranch}"), "See file: {$composerPath}");
    }

    public function testPhpRequirements(): void
    {
        $composerPath = PROJECT_ROOT . '/composer.json';
        $composerJson = json($composerPath);

        isSame($this->phpVersion, $composerJson->find('require.php'), "See file: {$composerPath}");
    }
}
