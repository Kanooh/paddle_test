<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Maps\ContentType\Maps\Common\NodeShowBreadcrumbOptionTest.
 */

namespace Kanooh\Paddle\App\Maps\ContentType\Maps\Common;

use Kanooh\Paddle\Apps\Maps;
use Kanooh\Paddle\Core\ContentType\Base\NodeShowBreadcrumbOptionTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class NodeShowBreadcrumbOptionTest
 * @package Kanooh\Paddle\App\Maps\ContentType\Maps\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeShowBreadcrumbOptionTest extends NodeShowBreadcrumbOptionTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $service = new AppService($this, $this->userSessionService);
        $service->enableApp(new Maps);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createMapsPageViaUI($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'paddle_maps_page';
    }
}
