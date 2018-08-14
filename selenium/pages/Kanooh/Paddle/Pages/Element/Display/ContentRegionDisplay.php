<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Display\ContentRegionDisplay.
 */

namespace Kanooh\Paddle\Pages\Element\Display;

use Kanooh\Paddle\Pages\Element\Region\PaddlePanelsRegion;

/**
 * A fixed display for pages showing content regions.
 *
 * This is always using the paddle_2_col_9_3_bottom layout.
 *
 * Currently used for basic pages and for the configuration page of the content
 * regions.
 */
class ContentRegionDisplay extends PaddlePanelsDisplay
{

    /**
     * {@inheritdoc}
     */
    protected $supportedLayouts = array(
        'paddle_2_col_9_3_bottom' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3BottomLayout',
        'paddle_2_col_9_3_d' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3VariantDLayout',
        'paddle_celebi' => '\Kanooh\Paddle\Pages\Element\Layout\PaddleCelebi',
        // CIRRO pages require this layout.
        'paddle_3_col_c' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle3ColVariantCLayout',
        // EBL pages require this layout.
        'paddle_2_cols_3_cols_d' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3ColVariantDLayout',
    );

    /**
     * {@inheritdoc}
     */
    protected function getCurrentRegions()
    {
        $regions = array();
        foreach ($this->layout->getRegions() as $id => $name) {
            // Only the right and bottom regions are editable through the UI
            // for node types with content regions support.
            // Only CIRRO pages have the 5_b layout editable as well.
            if (in_array($id, array('right', 'bottom', '5_b', 'row_2_left', 'row_2_middle', 'row_2_right'))) {
                $regions[$id] = new PaddlePanelsRegion(
                    $this->webdriver,
                    $id,
                    $name,
                    $this->getRegionXPathSelector($id)
                );
            }
        }
        return $regions;
    }

    /**
     * Instantiate the locked region from a Content Regions layout.
     *
     * @return PaddlePanelsRegion
     *   The locked region from a Content Regions layout.
     */
    public function getLockedRegion()
    {
        foreach ($this->layout->getRegions() as $id => $name) {
            // The left region isn't editable through the UI.
            if ($id == 'left') {
                return new PaddlePanelsRegion(
                    $this->webdriver,
                    $id,
                    $name,
                    $this->getRegionXPathSelector($id),
                    true
                );
            }
        }
    }
}
