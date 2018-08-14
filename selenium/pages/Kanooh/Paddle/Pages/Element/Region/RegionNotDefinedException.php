<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Region\RegionNotDefinedException.
 */

namespace Kanooh\Paddle\Pages\Element\Region;

/**
 * Exception for undefined regions.
 */
class RegionNotDefinedException extends \RuntimeException
{

    /**
     * Constructor for the RegionNotDefinedException.
     *
     * @param string $name
     *   The expected region.
     */
    public function __construct($name)
    {
        $message = 'The region ' . htmlentities($name) . ' is not defined.';
        parent::__construct($message);
    }
}
