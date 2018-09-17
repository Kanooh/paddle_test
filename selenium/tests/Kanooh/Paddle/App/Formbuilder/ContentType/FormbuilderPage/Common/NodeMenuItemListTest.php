<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Formbuilder\ContentType\FormbuilderPage\Common\NodeMenuItemListTest.
 */

namespace Kanooh\Paddle\App\Formbuilder\ContentType\FormbuilderPage\Common;

use Kanooh\Paddle\Apps\Formbuilder;
use Kanooh\Paddle\Core\ContentType\Base\NodeMenuItemListTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class NodeMenuItemListTest
 * @package Kanooh\Paddle\App\Formbuilder\ContentType\FormbuilderPage\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeMenuItemListTest extends NodeMenuItemListTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $service = new AppService($this, $this->userSessionService);
        $service->enableApp(new Formbuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createFormbuilderPage($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'paddle_formbuilder_page';
    }
}
