<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CompanyGuide\ContentType\CompanyPage\Common\NodeRateTest.
 */

namespace Kanooh\Paddle\App\CompanyGuide\ContentType\CompanyPage\Common;

use Kanooh\Paddle\Apps\CompanyGuide;
use Kanooh\Paddle\App\Rate\ContentType\Base\NodeRateTestBase;

/**
 * NodeRateTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeRateTest extends NodeRateTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new CompanyGuide);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createCompanyPage($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'company_page';
    }
}
