<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Maps\ContentType\Maps\Common\NodeCreationLanguageTest.
 */

namespace Kanooh\Paddle\App\Maps\ContentType\Maps\Common;

use Kanooh\Paddle\Apps\Maps;
use Kanooh\Paddle\App\Multilingual\ContentType\Base\NodeCreationLanguageTestBase;

/**
 * Class NodeDeleteButtonTest
 * @package Kanooh\Paddle\App\Maps\ContentType\Maps\Common
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

        $this->appService->enableApp(new Maps);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'paddle_maps_page';
    }
}
