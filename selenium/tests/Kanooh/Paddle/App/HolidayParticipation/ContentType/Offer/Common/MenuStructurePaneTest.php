<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\HolidayParticipation\ContentType\Offer\Common\MenuStructurePaneTest.
 */

namespace Kanooh\Paddle\App\HolidayParticipation\ContentType\Offer\Common;

use Kanooh\Paddle\Apps\HolidayParticipation;
use Kanooh\Paddle\Core\ContentType\Base\MenuStructurePaneTestBase;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class MenuStructurePaneTest
 * @package Kanooh\Paddle\App\HolidayParticipation\ContentType\Offer\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class MenuStructurePaneTest extends MenuStructurePaneTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $service = new AppService($this, $this->userSessionService);
        $service->enableApp(new HolidayParticipation);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOfferPage($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getLayoutPage()
    {
        return new LayoutPage($this);
    }
}
