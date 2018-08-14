<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Maps\ContentType\Maps\Common\ContentDiscoveryTest.
 */

namespace Kanooh\Paddle\App\Maps\ContentType\Maps\Common;

use Kanooh\Paddle\Apps\Maps;
use Kanooh\Paddle\Core\ContentType\Base\ContentDiscoveryTestBase;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\MapsLayoutPage;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class ContentDiscoveryTest.
 * @package Kanooh\Paddle\App\Maps\ContentType\Maps\Common
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
        $this->appService->enableApp(new Maps);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createMapsPage($title);
    }

    /**
     * {@inheritdoc}
     *
     * @return MapsLayoutPage
     */
    public function getLayoutPage()
    {
        return new MapsLayoutPage($this);
    }
}
