<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Links\LinkPresentException.
 */

namespace Kanooh\Paddle\Pages\Element\Links;

/**
 * Exception thrown when a link that should not be present is present.
 */
class LinkPresentException extends LinksException
{

    /**
     * Constructor for the LinkPresentException.
     *
     * @param string $name
     *   The name of the link.
     */
    public function __construct($name)
    {
        parent::__construct('The link "' . $name . '" is present.');
    }
}
