<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Display\LayoutNotDefinedException.
 */

namespace Kanooh\Paddle\Pages\Element\Display;

/**
 * Exception for undefined layouts.
 */
class LayoutNotDefinedException extends \RuntimeException
{

    /**
     * Constructor for the LayoutNotDefinedException.
     *
     * @param string $name
     *   The undefined layout.
     */
    public function __construct($name)
    {
        $message = 'The layout ' . htmlentities($name) . ' is not defined.';
        parent::__construct($message);
    }
}
