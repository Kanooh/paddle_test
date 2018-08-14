<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Region\RegionPaneUuidNotFoundException.
 */

namespace Kanooh\Paddle\Pages\Element\Region;

/**
 * Exception for missing Pane UUIDs.
 */
class RegionPaneUuidNotFoundException extends \RuntimeException
{

    /**
     * Constructor for the RegionPaneUuidNotFoundException.
     */
    public function __construct()
    {
        $message = 'The uuid for a pane is not found on the page.';
        parent::__construct($message);
    }
}
