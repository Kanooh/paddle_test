<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Maps\ContentType\Maps\Common\NodeRateTest.
 */

namespace Kanooh\Paddle\App\Maps\ContentType\Maps\Common;

use Kanooh\Paddle\Apps\Maps;
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

        $this->appService->enableApp(new Maps);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createMapsPage($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'paddle_maps_page';
    }
}
