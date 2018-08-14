<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CompanyGuide\ContentType\CompanyPage\Common\NodeMetadataSummaryTest.
 */

namespace Kanooh\Paddle\App\CompanyGuide\ContentType\CompanyPage\Common;

use Kanooh\Paddle\Apps\CompanyGuide;
use Kanooh\Paddle\Core\ContentType\Base\NodeMetadataSummaryTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class NodeMetadataSummaryTest
 * @package Kanooh\Paddle\App\CompanyGuide\ContentType\CompanyPage\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeMetadataSummaryTest extends NodeMetadataSummaryTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
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
