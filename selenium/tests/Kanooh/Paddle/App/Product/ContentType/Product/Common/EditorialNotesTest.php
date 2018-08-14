<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Product\ContentType\Product\Common\EditorialNotesTest.
 */

namespace Kanooh\Paddle\App\Product\ContentType\Product\Common;

use Kanooh\Paddle\Apps\Product;
use Kanooh\Paddle\Core\ContentType\Base\EditorialNotesTestBase;

/**
 * EditorialNotesTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class EditorialNotesTest extends EditorialNotesTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new Product);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createProductPage($title);
    }
}
