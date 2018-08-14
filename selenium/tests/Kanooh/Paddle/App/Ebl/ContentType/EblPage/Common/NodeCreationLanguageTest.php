<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Ebl\ContentType\EblPage\Common\NodeCreationLanguageTest.
 */

namespace Kanooh\Paddle\App\Ebl\ContentType\EblPage\Common;

use Kanooh\Paddle\Apps\Ebl;
use Kanooh\Paddle\App\Multilingual\ContentType\Base\NodeCreationLanguageTestBase;

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

        $this->appService->enableApp(new Ebl);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'paddle_ebl_page';
    }
}
