<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\PaddleMaintenancePage.
 */

namespace Kanooh\Paddle\Pages;

/**
 * A page you only get when Paddle maintenance mode is enabled.
 */
class PaddleMaintenancePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        parent::waitUntilPageIsLoaded();

        // Ensure Paddle maintenance mode is on.
        $this->webdriver->byCssSelector('body.paddle-maintenance-mode');
    }
}
