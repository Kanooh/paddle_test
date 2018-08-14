<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Toolbar\ToolbarButtonNotPresentException.
 */

namespace Kanooh\Paddle\Pages\Element\Toolbar;

/**
 * Exception thrown when a toolbar button is not present.
 */
class ToolbarButtonNotPresentException extends ToolbarException
{

    /**
     * Constructor for the ToolbarButtonNotPresentException.
     *
     * @param string $button
     *   The name of the absent button.
     */
    public function __construct($button)
    {
        parent::__construct('The toolbar button "' . $button . '" is not present.');
    }
}
