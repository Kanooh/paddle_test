<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Publication\ContentType\Publication\Common\ReadOnlyRoleTest.
 */

namespace Kanooh\Paddle\App\Publication\ContentType\Publication\Common;

use Kanooh\Paddle\Apps\Publication;
use Kanooh\Paddle\Core\ContentType\Base\ReadOnlyRoleTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * ReadOnlyRoleTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ReadOnlyRoleTest extends ReadOnlyRoleTestBase
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
