<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CompanyGuide\ContentType\CompanyPage\Common\NodeRevisionsTest.
 */

namespace Kanooh\Paddle\App\CompanyGuide\ContentType\CompanyPage\Common;

use Kanooh\Paddle\Apps\CompanyGuide;
use Kanooh\Paddle\Core\ContentType\Base\NodeRevisionsTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class NodeRevisionsTest
 * @package Kanooh\Paddle\App\CompanyGuide\ContentType\CompanyPage\Common
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeRevisionsTest extends NodeRevisionsTestBase
{
    /**
     * {@inheritdoc}
     */

    public function setupPage()
    {
        parent::setUpPage();

        $service = new AppService($this, $this->userSessionService);
        $service->enableApp(new CompanyGuide);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createCompanyPage($title);
    }
}
