<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Ebl\ContentType\EblPage\Common\ThemeTest.
 */

namespace Kanooh\Paddle\App\Ebl\ContentType\EblPage\Common;

use Kanooh\Paddle\Apps\Ebl;
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

        $this->appService->enableApp(new Ebl);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createEblPage($title);
    }
}
