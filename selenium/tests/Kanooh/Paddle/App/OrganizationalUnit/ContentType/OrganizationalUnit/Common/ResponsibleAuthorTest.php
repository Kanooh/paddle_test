<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common\ResponsibleAuthorTest.
 */

namespace Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common;

use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Core\ContentType\Base\ResponsibleAuthorTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class ResponsibleAuthorTest
 * @package Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common
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
        $service->enableApp(new OrganizationalUnit);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOrganizationalUnitViaUI($title);
    }
}
