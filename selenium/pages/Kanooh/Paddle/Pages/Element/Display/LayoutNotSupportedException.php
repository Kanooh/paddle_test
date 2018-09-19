<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Display\LayoutNotSupportedException.
 */

namespace Kanooh\Paddle\Pages\Element\Display;

/**
 * Exception for unsupported layouts.
 */
class LayoutNotSupportedException extends DisplayException
{

    /**
     * Constructor for the LayoutNotSupportedException.
     *
     * @param string $name
     *   The unsupported layout.
     */
    public function __construct($name)
    {
        $message = 'The layout ' . htmlentities($name) . ' is not supported by the current display.';
        parent::__construct($message);
    }
}
