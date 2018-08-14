<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Publication\ContentType\Publication\Common\UnpublishLiveRevisionTest.
 */

namespace Kanooh\Paddle\App\Publication\ContentType\Publication\Common;

use Kanooh\Paddle\Apps\Publication;
use Kanooh\Paddle\Core\ContentType\Base\UnpublishLiveRevisionTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * UnpublishLiveRevisionTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UnpublishLiveRevisionTest extends UnpublishLiveRevisionTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $service = new AppService($this, $this->userSessionService);
        $service->enableApp(new Publication);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createPublicationPage($title);
    }
}
