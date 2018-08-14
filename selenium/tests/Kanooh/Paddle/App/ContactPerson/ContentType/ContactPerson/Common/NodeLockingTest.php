<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common\NodeLockingTest.
 */

namespace Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common;

use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Core\ContentType\Base\NodeLockingTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class NodeLockingTest
 * @package Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common
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
        $service->enableApp(new ContactPerson);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createContactPerson($title);
    }
}
