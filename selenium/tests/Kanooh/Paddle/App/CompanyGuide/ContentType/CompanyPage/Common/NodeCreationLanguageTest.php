<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CompanyGuide\ContentType\CompanyPage\Common\NodeCreationLanguageTest.
 */

namespace Kanooh\Paddle\App\CompanyGuide\ContentType\CompanyPage\Common;

use Kanooh\Paddle\Apps\CompanyGuide;
use Kanooh\Paddle\App\Multilingual\ContentType\Base\NodeCreationLanguageTestBase;

/**
 * Class NodeDeleteButtonTest
 * @package Kanooh\Paddle\App\CompanyGuide\ContentType\CompanyPage\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeCreationLanguageTest extends NodeCreationLanguageTestBase
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
