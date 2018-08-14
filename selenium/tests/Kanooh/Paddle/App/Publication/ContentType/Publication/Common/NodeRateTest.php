<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Publication\ContentType\Publication\Common\NodeRateTest.
 */

namespace Kanooh\Paddle\App\Publication\ContentType\Publication\Common;

use Kanooh\Paddle\App\Rate\ContentType\Base\NodeRateTestBase;
use Kanooh\Paddle\Apps\Publication;

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

    public function setupPage()
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

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'paddle_publication';
    }
}
