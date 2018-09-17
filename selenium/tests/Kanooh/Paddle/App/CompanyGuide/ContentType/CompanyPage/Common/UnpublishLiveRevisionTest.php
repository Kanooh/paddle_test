<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CompanyGuide\ContentType\CompanyPage\Common\UnpublishLiveRevisionTest.
 */

namespace Kanooh\Paddle\App\CompanyGuide\ContentType\CompanyPage\Common;

use Kanooh\Paddle\Apps\CompanyGuide;
use Kanooh\Paddle\Core\ContentType\Base\UnpublishLiveRevisionTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class UnpublishLiveRevisionTest
 * @package Kanooh\Paddle\App\CompanyGuide\ContentType\CompanyPage\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UnpublishLiveRevisionTest extends UnpublishLiveRevisionTestBase
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
        $this->appService->enableApp(new CompanyGuide);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createCompanyPage($title);
    }
}
