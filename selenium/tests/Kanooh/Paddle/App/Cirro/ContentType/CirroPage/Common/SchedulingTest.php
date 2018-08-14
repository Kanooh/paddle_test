<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Cirro\ContentType\CirroPage\Common\SchedulingTest.
 */

namespace Kanooh\Paddle\App\Cirro\ContentType\CirroPage\Common;

use Kanooh\Paddle\Apps\Cirro;
use Kanooh\Paddle\Core\ContentType\Base\SchedulingTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * SchedulingTest class.
 *
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
