<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Region\RadioButtonNotDefinedException.
 */

namespace Kanooh\Paddle\Pages\Element\Form;

/**
 * Exception for an undefined radio button.
 */
class RadioButtonNotDefinedException extends \RuntimeException
{

    /**
     * Constructor for the RadioButtonNotDefinedException.
     *
     * @param string $name
     *   The machine name of the radio button that is not defined.
     */
    public function __construct($name)
    {
        $message = 'The radio button with the name ' . $name . ' is not defined.';
        parent::__construct($message);
    }
}
