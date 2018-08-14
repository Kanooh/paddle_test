<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Maps\ContentType\Maps\Common\ResponsibleAuthorTest.
 */

namespace Kanooh\Paddle\App\Maps\ContentType\Maps\Common;

use Kanooh\Paddle\Apps\Maps;
use Kanooh\Paddle\Core\ContentType\Base\ResponsibleAuthorTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class ResponsibleAuthorTest
 * @package Kanooh\Paddle\App\Maps\ContentType\Maps\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ResponsibleAuthorTest extends ResponsibleAuthorTestBase
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
        return $this->contentCreationService->createMapsPage($title);
    }
}
