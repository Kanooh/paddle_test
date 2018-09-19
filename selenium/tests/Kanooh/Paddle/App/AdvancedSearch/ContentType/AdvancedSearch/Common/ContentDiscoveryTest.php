<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common\ContentDiscoveryTest.
 */

namespace Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common;

use Kanooh\Paddle\Apps\AdvancedSearch;
use Kanooh\Paddle\Core\ContentType\Base\ContentDiscoveryTestBase;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\AdvancedSearchLayoutPage;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class ContentDiscoveryTest.
 * @package Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ContentDiscoveryTest extends ContentDiscoveryTestBase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new AdvancedSearch);
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
     *
     * @return AdvancedSearchLayoutPage
     */
    public function getLayoutPage()
    {
        return new AdvancedSearchLayoutPage($this);
    }
}
