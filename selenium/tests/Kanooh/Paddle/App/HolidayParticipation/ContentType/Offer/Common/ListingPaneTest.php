<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\HolidayParticipation\ContentType\Offer\Common\ListingPaneTest.
 */

namespace Kanooh\Paddle\App\HolidayParticipation\ContentType\Offer\Common;

use Kanooh\Paddle\Apps\HolidayParticipation;
use Kanooh\Paddle\Core\ContentType\Base\ListingPaneTestBase;

/**
 * Class ListingPaneTest
 * @package Kanooh\Paddle\App\HolidayParticipation\ContentType\Offer\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ListingPaneTest extends ListingPaneTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

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
