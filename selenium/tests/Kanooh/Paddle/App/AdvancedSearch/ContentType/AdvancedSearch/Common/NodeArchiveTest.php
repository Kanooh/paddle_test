<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common\NodeArchiveTest.
 */

namespace Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common;

use Kanooh\Paddle\Apps\AdvancedSearch;
use Kanooh\Paddle\Core\ContentType\Base\NodeArchiveTestBase;

/**
 * NodeArchiveTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeArchiveTest extends NodeArchiveTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new AdvancedSearch);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createAdvancedSearchPage($title);
    }
}
