<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Publication\ContentType\Publication\Common\SocialMediaTest.
 */

namespace Kanooh\Paddle\App\Publication\ContentType\Publication\Common;

use Kanooh\Paddle\App\SocialMedia\ContentType\Base\SocialMediaTestBase;
use Kanooh\Paddle\Apps\Publication;

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

        $this->appService->enableApp(new Publication);
    }


    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createPublicationPage($title);
    }
}
