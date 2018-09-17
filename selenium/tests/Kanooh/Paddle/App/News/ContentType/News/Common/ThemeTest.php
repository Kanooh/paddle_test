<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\News\ContentType\News\Common\ThemeTest.
 */

namespace Kanooh\Paddle\App\News\ContentType\News\Common;

use Kanooh\Paddle\Apps\News;
use Kanooh\Paddle\Core\ContentType\Base\ThemeTestBase;

/**
 * Class ThemeTest.
 * @package Kanooh\Paddle\App\News\ContentType\News\Common
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

        $this->appService->enableApp(new News);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createNewsItemViaUI($title);
    }
}
