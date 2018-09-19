<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common\SocialMediaTest.
 */

namespace Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common;

use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\App\SocialMedia\ContentType\Base\SocialMediaTestBase;

/**
 * SocialMediaTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SocialMediaTest extends SocialMediaTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new OrganizationalUnit);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOrganizationalUnit($title);
    }
}
