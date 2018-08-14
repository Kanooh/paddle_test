<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SimpleContact\ContentType\SimpleContact\Common\SchedulingTest.
 */

namespace Kanooh\Paddle\App\SimpleContact\ContentType\SimpleContact\Common;

use Kanooh\Paddle\Apps\SimpleContact;
use Kanooh\Paddle\Core\ContentType\Base\SchedulingTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class SchedulingTest
 * @package Kanooh\Paddle\App\SimpleContact\ContentType\SimpleContact\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SchedulingTest extends SchedulingTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $service = new AppService($this, $this->userSessionService);
        $service->enableApp(new SimpleContact);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createSimpleContact($title);
    }
}
