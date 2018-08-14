<?php

/**
 * @file
 * \Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common\NodeMenuItemListTest.
 */

namespace Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common;

use Kanooh\Paddle\Core\ContentType\Base\NodeMenuItemListTestBase;
use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class NodeMenuItemListTest
 * @package Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common
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
        $service->enableApp(new OrganizationalUnit);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOrganizationalUnit($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'organizational_unit';
    }
}
