<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Product\ContentType\Product\Common\FeaturedImageTest.
 */

namespace Kanooh\Paddle\App\Poll\ContentType\Poll\Common;

use Kanooh\Paddle\Apps\Poll;
use Kanooh\Paddle\Core\ContentType\Base\FeaturedImageTestBase;

/**
 * FeaturedImageTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class FeaturedImageTest extends FeaturedImageTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new Poll);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createPollPage($title);
    }
}
