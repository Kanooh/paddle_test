<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Maps\ContentType\Maps\Common\NodeLockingTest.
 */

namespace Kanooh\Paddle\App\Maps\ContentType\Maps\Common;

use Kanooh\Paddle\Apps\Maps;
use Kanooh\Paddle\Core\ContentType\Base\NodeLockingTestBase;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\MapsLayoutPage;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class NodeLockingTest
 * @package Kanooh\Paddle\App\Maps\ContentType\Maps\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeLockingTest extends NodeLockingTestBase
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

    /**
     * {@inheritdoc}
     */
    protected function pageLayoutPage()
    {
        return new MapsLayoutPage($this);
    }
}
