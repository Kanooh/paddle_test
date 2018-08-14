<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Formbuilder\ContentType\FormbuilderPage\Common\NodeRevisionsTest.
 */

namespace Kanooh\Paddle\App\Formbuilder\ContentType\FormbuilderPage\Common;

use Kanooh\Paddle\Apps\Formbuilder;
use Kanooh\Paddle\Core\ContentType\Base\NodeRevisionsTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class NodeRevisionsTest
 * @package Kanooh\Paddle\App\Formbuilder\ContentType\FormbuilderPage\Common
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeRevisionsTest extends NodeRevisionsTestBase
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
        return $this->contentCreationService->createFormbuilderPageViaUI($title);
    }
}
