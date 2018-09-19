<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common\NodeCreationLanguageTest.
 */

namespace Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common;

use Kanooh\Paddle\Apps\AdvancedSearch;
use Kanooh\Paddle\App\Multilingual\ContentType\Base\NodeCreationLanguageTestBase;

/**
 * Class NodeDeleteButtonTest
 * @package Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common
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

        $this->appService->enableApp(new AdvancedSearch);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'paddle_advanced_search_page';
    }
}
