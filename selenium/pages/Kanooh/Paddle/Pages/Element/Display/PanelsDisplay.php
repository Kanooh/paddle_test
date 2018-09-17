<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Display\PanelsDisplay.
 */

namespace Kanooh\Paddle\Pages\Element\Display;

/**
 * The Panels display class.
 */
class PanelsDisplay extends Display
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '';

    /**
     * Checks if the display is in editor status.
     *
     * This is retrieved from the current browser page.
     *
     * @todo This code is as of yet untested. Our users currently never have
     *   access to this Panels display. Verify if this works once they do.
     *
     * @return bool
     *   TRUE if the display is in editor status. FALSE otherwise.
     */
    protected function getEditorStatus()
    {
        return $this->getElementCountByXPath('//div[@id="panels-dnd-main"]');
    }

    /**
     * {@inheritdoc}
     */
    protected function getRegionXPathSelector($id)
    {
        return '//div[contains(@class, "panel-region-' . str_replace('_', '-', $id) . '")]';
    }
}
