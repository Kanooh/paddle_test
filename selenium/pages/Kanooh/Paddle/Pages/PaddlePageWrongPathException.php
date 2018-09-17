<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\PaddlePageWrongPathException.
 */

namespace Kanooh\Paddle\Pages;

/**
 * Exception for failed path checks.
 */
class PaddlePageWrongPathException extends PaddlePageException
{

    /**
     * Constructor for the PaddlePageWrongPathException.
     *
     * @param string $expected_path
     *   The expected path.
     * @param string $actual_path
     *   Optional: the actual path.
     */
    public function __construct($expected_path, $actual_path = '')
    {
        $message = 'Browser is not on the expected path "' . htmlentities($expected_path) . '".';
        empty($actual_path) || $message .= ' Actual path: "' . htmlentities($actual_path) . '".';
        parent::__construct($message);
    }
}
