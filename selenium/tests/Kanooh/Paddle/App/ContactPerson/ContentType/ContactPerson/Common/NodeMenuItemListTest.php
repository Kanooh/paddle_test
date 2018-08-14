<?php

/**
 * @file
 * \Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common\NodeMenuItemListTest.
 */

namespace Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common;

use Kanooh\Paddle\Core\ContentType\Base\NodeMenuItemListTestBase;
use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class NodeMenuItemListTest
 * @package Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeMenuItemListTest extends NodeMenuItemListTestBase
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
    public function setupNode($first_name = null, $last_name = null)
    {
        return $this->contentCreationService->createContactPerson($first_name, $last_name);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'contact_person';
    }
}
