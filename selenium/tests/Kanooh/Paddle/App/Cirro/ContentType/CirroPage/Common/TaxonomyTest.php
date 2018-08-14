<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Cirro\ContentType\CirroPage\Common\TaxonomyTest.
 */

namespace Kanooh\Paddle\App\Cirro\ContentType\CirroPage\Common;

use Kanooh\Paddle\Apps\Cirro;
use Kanooh\Paddle\Core\ContentType\Base\TaxonomyTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * TaxonomyTest class.
 *
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
        $service->enableApp(new Cirro);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createCirroPage($title);
    }
}
