<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage\AppsOverviewAvailablePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage;

use Kanooh\Paddle\Apps\AppInterface;

/**
 * The Paddle Store Available overview page class.
 */
class AppsOverviewAvailablePage extends AppsOverviewPage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddlet_store/available';

    /**
     * Queue the app for installation.
     *
     * @param AppInterface $app
     *   The App to enable.
     */
    public function enableApp(AppInterface $app)
    {
        $this->go();

        $app_element = $this->appElement($app);
        $app_element->links->linkEnable->click();

        // Confirm the activation in the dialog.
        $confirmation_modal = new ConfirmAppActivationModal($this->webdriver);
        $confirmation_modal->waitUntilOpened();
        $confirmation_modal->submit();
        $confirmation_modal->waitUntilClosed();
    }
}
