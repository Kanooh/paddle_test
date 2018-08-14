<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\HttpRequestBadDataException.
 */

namespace Kanooh\Paddle\Utilities\HttpRequest;

/**
 * Exception for data in a bad format.
 */
class HttpRequestBadDataException extends HttpRequestException
{

    /**
     * Constructor for the HttpRequestBadDataException.
     *
     * @param mixed $data
     *   The data in the incorrect format.
     */
    public function __construct($data)
    {
        $type = gettype($data);
        $message = 'The supplied data is in an incorrect format. Expected an array, but type ' . $type . ' was found.';
        parent::__construct($message);
    }
}
