<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Product\ContentType\Product\Common\TaxonomyTest.
 */

namespace Kanooh\Paddle\App\Poll\ContentType\Poll\Common;

use Kanooh\Paddle\Apps\Poll;
use Kanooh\Paddle\Core\ContentType\Base\TaxonomyTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class TaxonomyTest
 * @package Kanooh\Paddle\App\Product\ContentType\Product\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class TaxonomyTest extends TaxonomyTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $service = new AppService($this, $this->userSessionService);
        $service->enableApp(new Poll);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createPollPage($title);
    }
}
