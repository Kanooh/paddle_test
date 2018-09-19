<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage.
 */

namespace Kanooh\Paddle\Pages\Admin\DashboardPage;

use Kanooh\Paddle\Pages\AdminPage;

/**
 * The administration dashboard.
 */
class DashboardPage extends AdminPage
{

    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/dashboard';

    /**
     * {@inheritdoc}
     */
    public function checkPath()
    {
        // This page is also accessible from the 'admin' path.
        $current_url = parse_url($this->webdriver->url());
        $current_path = trim($current_url['path'], '/');
        if ($current_path != 'admin') {
            parent::checkPath();
        }
    }
}
