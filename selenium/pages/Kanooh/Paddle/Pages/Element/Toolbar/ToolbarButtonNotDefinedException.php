<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Toolbar\ToolbarButtonNotDefinedException.
 */

namespace Kanooh\Paddle\Pages\Element\Toolbar;

/**
 * Exception thrown when a toolbar button is not defined.
 */
class ToolbarButtonNotDefinedException extends ToolbarException
{

    /**
     * Constructor for the ToolbarButtonNotDefinedException.
     *
     * @param string $name
     *   The name of the undefined button.
     */
    public function __construct($name)
    {
        $message = 'The toolbar button "' . htmlentities($name) . '" is not defined.';
        parent::__construct($message);
    }
}
