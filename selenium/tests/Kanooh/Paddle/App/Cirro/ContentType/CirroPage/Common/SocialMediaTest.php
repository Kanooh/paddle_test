<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Cirro\ContentType\CirroPage\Common\SocialMediaTest.
 */

namespace Kanooh\Paddle\App\Cirro\ContentType\CirroPage\Common;

use Kanooh\Paddle\App\SocialMedia\ContentType\Base\SocialMediaTestBase;
use Kanooh\Paddle\Apps\Cirro;

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

        $this->appService->enableApp(new Cirro);
    }


    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createCirroPage($title);
    }
}
