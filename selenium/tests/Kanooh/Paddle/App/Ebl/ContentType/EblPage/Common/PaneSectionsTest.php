<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Ebl\ContentType\EblPage\Common\PaneSectionsTest.
 */

namespace Kanooh\Paddle\App\Ebl\ContentType\EblPage\Common;

use Kanooh\Paddle\Apps\Ebl;
use Kanooh\Paddle\Core\ContentType\Base\PaneSectionsTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * PaneSectionsTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneSectionsTest extends PaneSectionsTestBase
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
        return $this->contentCreationService->createEblPage($title);
    }
}
