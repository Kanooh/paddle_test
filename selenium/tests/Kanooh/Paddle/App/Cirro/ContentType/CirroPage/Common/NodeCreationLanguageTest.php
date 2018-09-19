<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Cirro\ContentType\CirroPage\Common\NodeCreationLanguageTest.
 */

namespace Kanooh\Paddle\App\Cirro\ContentType\CirroPage\Common;

use Kanooh\Paddle\App\Multilingual\ContentType\Base\NodeCreationLanguageTestBase;
use Kanooh\Paddle\Apps\Cirro;

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

        $this->appService->enableApp(new Cirro);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'paddle_cirro_page';
    }
}
