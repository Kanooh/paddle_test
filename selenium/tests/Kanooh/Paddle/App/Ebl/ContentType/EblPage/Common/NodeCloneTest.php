<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Ebl\ContentType\EblPage\Common\NodeCloneTest.
 */

namespace Kanooh\Paddle\App\Ebl\ContentType\EblPage\Common;

use Kanooh\Paddle\Apps\Ebl;
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

        $this->appService->enableApp(new Ebl);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createEblPage($title);
    }
}
