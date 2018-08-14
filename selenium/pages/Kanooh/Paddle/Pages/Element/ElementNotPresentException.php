<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\ElementNotPresentException.
 */

namespace Kanooh\Paddle\Pages\Element;

/**
 * Exception for not present element.
 */
class ElementNotPresentException extends \RuntimeException
{

    /**
     * Constructor for the ElementNotPresentException.
     *
     * @param string $xpath
     *   The expected xpath of the element.
     */
    public function __construct($xpath)
    {
        $message = 'The element with xpath <' . $xpath . '> is not found.';
        parent::__construct($message);
    }
}
