<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Formbuilder\ContentType\FormbuilderPage\Common\TaxonomyTest.
 */

namespace Kanooh\Paddle\App\Formbuilder\ContentType\FormbuilderPage\Common;

use Kanooh\Paddle\Apps\Formbuilder;
use Kanooh\Paddle\Core\ContentType\Base\TaxonomyTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class TaxonomyTest
 * @package Kanooh\Paddle\App\Formbuilder\ContentType\FormbuilderPage\Common
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
        $service->enableApp(new Formbuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createFormbuilderPage($title);
    }
}
