<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CompanyGuide\ContentType\CompanyPage\Common\VideoPaneTest.
 */

namespace Kanooh\Paddle\App\CompanyGuide\ContentType\CompanyPage\Common;

use Kanooh\Paddle\Core\ContentType\Base\VideoPaneBaseTest;

/**
 * Class VideoPaneTest
 * @package Kanooh\Paddle\App\CompanyGuide\ContentType\CompanyPage\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class VideoPaneTest extends VideoPaneBaseTest
{

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createCompanyPage($title);
    }
}
