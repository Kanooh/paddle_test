<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\LandingPage\Common\VideoPaneTest.
 */

namespace Kanooh\Paddle\Core\ContentType\LandingPage\Common;

use Kanooh\Paddle\Core\ContentType\Base\VideoPaneBaseTest;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;

/**
 * VideoPaneTest class.
 *
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
        return $this->contentCreationService->createLandingPage(null, $title);
    }

    /**
     * {@inheritdoc}
     */
    protected function getLayoutPage()
    {
        return new PanelsContentPage($this);
    }
}
