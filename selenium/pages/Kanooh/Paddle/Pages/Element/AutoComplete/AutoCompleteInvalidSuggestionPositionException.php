<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\AutoComplete\AutoCompleteInvalidPositionValueException.
 */

namespace Kanooh\Paddle\Pages\Element\AutoComplete;

/**
 * Should be thrown when an invalid suggestion value is passed.
 */
class AutoCompleteInvalidPositionValueException extends AutoCompleteException
{
    /**
     * Constructor method.
     *
     * @param int $position
     *   Invalid suggestion position.
     */
    public function __construct($position = 0)
    {
        parent::__construct('Invalid position "' . $position . '" passed as suggestion.');
    }
}
