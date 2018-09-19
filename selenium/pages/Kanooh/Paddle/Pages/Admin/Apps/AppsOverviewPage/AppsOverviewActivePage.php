<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage\AppsOverviewActivePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage;

use Kanooh\Paddle\Apps\AppInterface;
use Kanooh\Paddle\Pages\Admin\Apps\InfoPage\InfoPage;

/**
 * The Paddle Store Active overview page class.
 */
class AppsOverviewActivePage extends AppsOverviewPage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddlet_store/active';

    /**
     * Disables the app and wait until it is disabled.
     *
     * @param AppInterface $app
     *   The
     */
    public function disableApp(AppInterface $app)
    {
        $this->go();

        $app_element = $this->appElement($app);
        $app_element->links->linkInfo->click();

        $info_page = new InfoPage($this->webdriver, $app);
        $info_page->checkArrival();
        $info_page->contextualToolbar->buttonDeactivate->click();

        // Wait for the message that declares a successful uninstall.
        $this->webdriver->waitUntilTextIsPresent('Disabled');
    }
}
