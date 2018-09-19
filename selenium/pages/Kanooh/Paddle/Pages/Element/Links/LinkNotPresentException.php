<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Links\LinkNotPresentException.
 */

namespace Kanooh\Paddle\Pages\Element\Links;

/**
 * Exception thrown when a link is not present.
 */
class LinkNotPresentException extends LinksException
{

    /**
     * Constructor for the LinkNotPresentException.
     *
     * @param string $name
     *   The name of the absent link.
     */
    public function __construct($name)
    {
        parent::__construct('The link "' . $name . '" is not present.');
    }
}
