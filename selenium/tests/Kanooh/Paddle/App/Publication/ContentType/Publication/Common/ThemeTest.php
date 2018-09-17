<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Publication\ContentType\Publication\Common\ThemeTest.
 */

namespace Kanooh\Paddle\App\Publication\ContentType\Publication\Common;

use Kanooh\Paddle\Apps\Publication;
use Kanooh\Paddle\Core\ContentType\Base\ThemeTestBase;

/**
 * ThemeTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ThemeTest extends ThemeTestBase
{
    /**
     * {@inheritdoc}
     */

    public function setupPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new Publication);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createPublicationPageViaUI($title);
    }
}
