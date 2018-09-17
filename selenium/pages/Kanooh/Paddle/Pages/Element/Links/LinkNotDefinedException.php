<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Links\LinkNotDefinedException.
 */

namespace Kanooh\Paddle\Pages\Element\Links;

/**
 * Exception thrown when a link is not defined.
 */
class LinkNotDefinedException extends LinksException
{

    /**
     * Constructor for the LinkNotDefinedException.
     *
     * @param string $name
     *   The name of the undefined link.
     */
    public function __construct($name)
    {
        parent::__construct('The link "' . $name . '" is not defined.');
    }
}
