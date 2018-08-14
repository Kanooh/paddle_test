<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Region\PaddlePanelsRegion.
 */

namespace Kanooh\Paddle\Pages\Element\Region;

/**
 * Base class for regions.
 */
class PaddlePanelsRegion extends PanelsIPERegion
{

    /**
     * The pane uuid data attribute name.
     *
     * @var string
     */
    protected $uuidDataAttribute = 'data-pane-uuid';
}
