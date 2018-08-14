<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Toolbar\ToolbarButtonPresentException.
 */

namespace Kanooh\Paddle\Pages\Element\Toolbar;

/**
 * Exception thrown when a toolbar button is unexpectedly present.
 */
class ToolbarButtonPresentException extends ToolbarException
{
    /**
     * Constructor for the ToolbarButtonPresentException.
     *
     * @param string $button
     *   The name of the button.
     */
    public function __construct($button)
    {
        parent::__construct('The toolbar button "' . $button . '" is present. It should be absent.');
    }
}
