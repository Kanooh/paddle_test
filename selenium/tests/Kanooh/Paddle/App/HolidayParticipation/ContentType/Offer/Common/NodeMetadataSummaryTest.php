<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\HolidayParticipation\ContentType\Offer\Common\NodeMetadataSummaryTest.
 */

namespace Kanooh\Paddle\App\HolidayParticipation\ContentType\Offer\Common;

use Kanooh\Paddle\Apps\HolidayParticipation;
use Kanooh\Paddle\Core\ContentType\Base\NodeMetadataSummaryTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class NodeMetadataSummaryTest
 * @package Kanooh\Paddle\App\HolidayParticipation\ContentType\Offer\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeMetadataSummaryTest extends NodeMetadataSummaryTestBase
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
        return $this->contentCreationService->createPollPageViaUI($title);
    }
}
