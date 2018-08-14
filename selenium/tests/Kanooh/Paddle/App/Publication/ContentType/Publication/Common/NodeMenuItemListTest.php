<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Publication\ContentType\Publication\Common\NodeMenuItemListTest.
 */

namespace Kanooh\Paddle\App\Publication\ContentType\Publication\Common;

use Kanooh\Paddle\Apps\Publication;
use Kanooh\Paddle\Core\ContentType\Base\NodeMenuItemListTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * NodeMenuItemListTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeMenuItemListTest extends NodeMenuItemListTestBase
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
