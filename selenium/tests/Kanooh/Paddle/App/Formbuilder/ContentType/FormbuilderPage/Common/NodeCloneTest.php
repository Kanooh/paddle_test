<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Formbuilder\ContentType\FormbuilderPage\Common\NodeCloneTest.
 */

namespace Kanooh\Paddle\App\Formbuilder\ContentType\FormbuilderPage\Common;

use Kanooh\Paddle\Apps\Formbuilder;
use Kanooh\Paddle\Core\ContentType\Base\NodeCloneTestBase;

/**
 * Class NodeCloneTest.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeCloneTest extends NodeCloneTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new Formbuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createFormbuilderPage($title);
    }
}
