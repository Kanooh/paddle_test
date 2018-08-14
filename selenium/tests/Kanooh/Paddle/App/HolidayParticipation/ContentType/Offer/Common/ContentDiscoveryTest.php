<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\HolidayParticipation\ContentType\Offer\Common\ContentDiscoveryTest.
 */

namespace Kanooh\Paddle\App\HolidayParticipation\ContentType\Offer\Common;

use Kanooh\Paddle\Apps\HolidayParticipation;
use Kanooh\Paddle\Core\ContentType\Base\ContentDiscoveryTestBase;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\CleanUpService;

/**
 * Class ContentDiscoveryTest.
 * @package Kanooh\Paddle\App\HolidayParticipation\ContentType\Offer\Common
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
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * @var boolean
     */
    protected $original_elysia_cron_disable;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->cleanUpService = new CleanUpService($this);
        // Delete the node entities.
        $this->cleanUpService->deleteEntities('node', 'offer');

        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new HolidayParticipation);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOfferPage($title);
    }
}
