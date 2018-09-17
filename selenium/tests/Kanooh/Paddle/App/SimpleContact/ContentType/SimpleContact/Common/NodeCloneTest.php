<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SimpleContact\ContentType\SimpleContact\Common\NodeCloneTest.
 */

namespace Kanooh\Paddle\App\SimpleContact\ContentType\SimpleContact\Common;

use Kanooh\Paddle\Apps\SimpleContact;
use Kanooh\Paddle\Core\ContentType\Base\NodeCloneTestBase;

/**
 * Class NodeCloneTest.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeCloneTest extends NodeCloneTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new SimpleContact);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createSimpleContact($title);
    }
}
