<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common\VideoPaneTest.
 */

namespace Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common;

use Kanooh\Paddle\Apps\AdvancedSearch;
use Kanooh\Paddle\Core\ContentType\Base\VideoPaneBaseTest;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\AdvancedSearchLayoutPage;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class VideoPaneTest
 * @package Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class VideoPaneTest extends VideoPaneBaseTest
{
    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $service = new AppService($this, $this->userSessionService);
        $service->enableApp(new AdvancedSearch);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createAdvancedSearchPage($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getLayoutPage()
    {
        return new AdvancedSearchLayoutPage($this);
    }
}
