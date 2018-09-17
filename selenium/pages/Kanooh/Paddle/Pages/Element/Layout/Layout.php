<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Layout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * Base class for a Panels layout.
 */
abstract class Layout
{

    /**
     * The layout metadata.
     *
     * @todo Split this up into separate properties.
     *
     * @var array
     *   An associative array of layout metadata with the following keys:
     *   - title: The human readable layout name.
     *   - category: The category to which the layout belongs.
     *   - regions: An associative array of region names, keyed on region
     *     machine name.
     */
    protected $info;

    /**
     * Returns the machine name of the layout.
     *
     * @return string
     *   The machine name of the layout.
     */
    public function id()
    {
        return $this->info['id'];
    }

    /**
     * Returns the layout metadata.
     *
     * @deprecated Remove this once the info is split up into separate
     *   properties.
     *
     * @return array
     *   An associative array of layout metadata with the following keys:
     *   - title: The human readable layout name.
     *   - category: The category to which the layout belongs.
     *   - regions: An associative array of region names, keyed on region
     *     machine name.
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Returns the available regions in this layout.
     *
     * Note that this only returns basic region information, not a full Region
     * object. If you need one, request it from your Display.
     *
     * @see \Kanooh\Paddle\Pages\Element\Display::region()
     * @see \Kanooh\Paddle\Pages\Element\Display::getRegions()
     * @see \Kanooh\Paddle\Pages\Element\Display::getRandomRegion()
     *
     * @return array
     *   An associative array of region human readable names, keyed by human
     *   machine name.
     */
    public function getRegions()
    {
        return $this->info['regions'];
    }
}
