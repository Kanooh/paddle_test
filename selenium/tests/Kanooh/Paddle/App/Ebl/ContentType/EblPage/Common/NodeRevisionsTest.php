<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Ebl\ContentType\EblPage\Common\NodeRevisionsTest.
 */

namespace Kanooh\Paddle\App\Ebl\ContentType\EblPage\Common;

use Kanooh\Paddle\Apps\Ebl;
use Kanooh\Paddle\Core\ContentType\Base\NodeRevisionsTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class NodeRevisionsTest
 * @package Kanooh\Paddle\App\Ebl\ContentType\EblPage\Common
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeRevisionsTest extends NodeRevisionsTestBase
{
    /**
     * {@inheritdoc}
     */

    public function setupPage()
    {
        parent::setUpPage();

        $service = new AppService($this, $this->userSessionService);
        $service->enableApp(new Ebl);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createEblPageViaUI($title);
    }
}
