<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Cirro\ContentType\CirroPage\Common\ReadOnlyRoleTest.
 */

namespace Kanooh\Paddle\App\Cirro\ContentType\CirroPage\Common;

use Kanooh\Paddle\Apps\Cirro;
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
        $service->enableApp(new Cirro);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createCirroPage($title);
    }
}
