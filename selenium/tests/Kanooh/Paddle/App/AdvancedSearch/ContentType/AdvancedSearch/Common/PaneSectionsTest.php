<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common\PaneSectionsTest.
 */

namespace Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common;

use Kanooh\Paddle\Apps\AdvancedSearch;
use Kanooh\Paddle\Core\ContentType\Base\PaneSectionsTestBase;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\AdvancedSearchLayoutPage;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class PaneSectionsTest
 * @package Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneSectionsTest extends PaneSectionsTestBase
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
    protected function getLayoutPage()
    {
        return new AdvancedSearchLayoutPage($this);
    }
}
