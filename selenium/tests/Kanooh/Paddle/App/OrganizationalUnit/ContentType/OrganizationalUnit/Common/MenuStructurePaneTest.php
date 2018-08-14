<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common\MenuStructurePaneTest.
 */

namespace Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common;

use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Core\ContentType\Base\MenuStructurePaneTestBase;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class MenuStructurePaneTest
 * @package Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class MenuStructurePaneTest extends MenuStructurePaneTestBase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new OrganizationalUnit);
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
    public function getLayoutPage()
    {
        return new LayoutPage($this);
    }
}
