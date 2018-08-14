<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Publication\ContentType\Publication\Common\NodeCreationLanguageTest.
 */

namespace Kanooh\Paddle\App\Publication\ContentType\Publication\Common;

use Kanooh\Paddle\App\Multilingual\ContentType\Base\NodeCreationLanguageTestBase;
use Kanooh\Paddle\Apps\Publication;

/**
 * NodeDeleteButtonTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeCreationLanguageTest extends NodeCreationLanguageTestBase
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
    public function getContentTypeName()
    {
        return 'paddle_publication';
    }
}
