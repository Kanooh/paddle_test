<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Display\DisplayNotInEditorModeException.
 */

namespace Kanooh\Paddle\Pages\Element\Display;

/**
 * Exception thrown when a Display is not in editor mode.
 */
class DisplayNotInEditorModeException extends DisplayException
{

    /**
     * Constructor for DisplayNotInEditorModeException.
     */
    public function __construct()
    {
        parent::__construct('The display is not in editor mode.');
    }
}
