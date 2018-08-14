<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Display\DisplayNotInViewModeException.
 */

namespace Kanooh\Paddle\Pages\Element\Display;

/**
 * Exception thrown when a Display is not in view mode.
 */
class DisplayNotInViewModeException extends DisplayException
{

    /**
     * Constructor for DisplayNotInViewModeException.
     */
    public function __construct()
    {
        parent::__construct('The display is not in view mode.');
    }
}
