<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Region\RegionNotPresentException.
 */

namespace Kanooh\Paddle\Pages\Element\Region;

/**
 * Exception for not present regions.
 */
class RegionNotPresentException extends \RuntimeException
{

    /**
     * Constructor for the RegionNotPresentException.
     *
     * @param string $name
     *   The expected region.
     */
    public function __construct($name)
    {
        $message = 'The region ' . htmlentities($name) . ' is not found.';
        parent::__construct($message);
    }
}
