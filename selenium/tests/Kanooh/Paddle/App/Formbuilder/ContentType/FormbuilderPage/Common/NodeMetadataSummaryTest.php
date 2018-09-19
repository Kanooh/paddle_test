<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Formbuilder\ContentType\FormbuilderPage\Common\NodeMetadataSummaryTest.
 */

namespace Kanooh\Paddle\App\Formbuilder\ContentType\FormbuilderPage\Common;

use Kanooh\Paddle\Apps\Formbuilder;
use Kanooh\Paddle\Core\ContentType\Base\NodeMetadataSummaryTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class NodeMetadataSummaryTest
 * @package Kanooh\Paddle\App\Formbuilder\ContentType\FormbuilderPage\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeMetadataSummaryTest extends NodeMetadataSummaryTestBase
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
