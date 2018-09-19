<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Modal\ModalFormElementNotPresentException.
 */

namespace Kanooh\Paddle\Pages\Element\Modal;

/**
 * Exception thrown when a link is not present.
 */
class ModalFormElementNotPresentException extends ModalException
{

    /**
     * Constructor for the ModalFormElementNotPresentException.
     *
     * @param string $name
     *   The name of the form element that is not present.
     */
    public function __construct($name)
    {
        parent::__construct('The form element "' . $name . '" is not present.');
    }
}
