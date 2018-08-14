<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Cirro\ContentType\CirroPage\Common\PaneSectionsTest.
 */

namespace Kanooh\Paddle\App\Cirro\ContentType\CirroPage\Common;

use Kanooh\Paddle\Apps\Cirro;
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
