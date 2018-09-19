<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\HolidayParticipation\ContentType\Offer\Common\NodeCommentTest.
 */

namespace Kanooh\Paddle\App\HolidayParticipation\ContentType\Offer\Common;

use Kanooh\Paddle\Apps\HolidayParticipation;
use Kanooh\Paddle\App\Comment\ContentType\Base\NodeCommentTestBase;

/**
 * Class NodeCommentTest.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeCommentTest extends NodeCommentTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupPage()
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

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'offer';
    }
}
