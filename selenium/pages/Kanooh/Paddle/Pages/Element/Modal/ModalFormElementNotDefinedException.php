<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Modal\ModalFormElementNotDefinedException.
 */

namespace Kanooh\Paddle\Pages\Element\Modal;

/**
 * Exception thrown when a link is not defined.
 */
class ModalFormElementNotDefinedException extends ModalException
{

    /**
     * Constructor for the ModalFormElementNotDefinedException.
     *
     * @param string $name
     *   The name of the undefined form element.
     */
    public function __construct($name)
    {
        parent::__construct('The form element "' . $name . '" is not defined.');
    }
}
