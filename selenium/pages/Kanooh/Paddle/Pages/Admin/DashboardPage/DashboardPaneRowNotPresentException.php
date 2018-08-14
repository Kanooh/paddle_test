<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPaneRowNotPresentException.
 */

namespace Kanooh\Paddle\Pages\Admin\DashboardPage;

/**
 * Exception thrown when a dashboard pane row is not present.
 */
class DashboardPaneRowNotPresentException extends \Exception
{
    /**
     * Constructor for the DashboardPaneRowNotPresentException.
     *
     * @param string $title
     *   The title of the absent row.
     */
    public function __construct($title)
    {
        parent::__construct('The dashboard pane row "' . $title . '" is not present.');
    }
}
