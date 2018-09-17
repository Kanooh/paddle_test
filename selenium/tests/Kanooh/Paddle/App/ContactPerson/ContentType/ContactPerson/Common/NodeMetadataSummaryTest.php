<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common\NodeMetadataSummaryTest.
 */

namespace Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common;

use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Core\ContentType\Base\NodeMetadataSummaryTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class NodeMetadataSummaryTest
 * @package Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeMetadataSummaryTest extends NodeMetadataSummaryTestBase
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
        return $this->contentCreationService->createContactPersonViaUI($title);
    }
}
