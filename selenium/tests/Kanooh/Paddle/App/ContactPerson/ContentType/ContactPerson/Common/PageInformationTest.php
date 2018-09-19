<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common\PageInformationTest.
 */

namespace Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common;

use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Core\ContentType\Base\PageInformationTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class PageInformationTest
 * @package Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PageInformationTest extends PageInformationTestBase
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
