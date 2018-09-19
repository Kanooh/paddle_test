<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SimpleContact\ContentType\SimpleContact\Common\FeaturedImageTest.
 */

namespace Kanooh\Paddle\App\SimpleContact\ContentType\SimpleContact\Common;

use Kanooh\Paddle\Apps\SimpleContact;
use Kanooh\Paddle\Core\ContentType\Base\FeaturedImageTestBase;

/**
 * FeaturedImageTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class FeaturedImageTest extends FeaturedImageTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new SimpleContact);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createSimpleContact($title);
    }
}
