<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Display\PanelsIPEDisplay.
 */

namespace Kanooh\Paddle\Pages\Element\Display;

use Kanooh\Paddle\Pages\Element\Region\PanelsIPERegion;

/**
 * The Panels IPE display class.
 */
class PanelsIPEDisplay extends PanelsDisplay
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[contains(@class, "panels-ipe-display-container")]';

    /**
     * Checks if the display is in editor status.
     *
     * This is retrieved from the current browser page. Please note that Panels
     * IPE allows to dynamically toggle the editor on and off so the status may
     * change depending on the user's actions.
     *
     * @todo This code is as of yet untested. Verify if this works once we add
     *   a test that actually toggles the IPE on and off.
     *
     * @return bool
     *   TRUE if the display is in editor status. FALSE otherwise.
     */
    protected function getEditorStatus()
    {
        $element = $this->webdriver->byXPath($this->xpathSelector);
        $classes = explode(' ', $element->attribute('class'));
        return in_array('panels-ipe-editing', $classes);
    }

    /**
     * {@inheritdoc}
     */
    protected function getRegionXPathSelector($id)
    {
        return '//div[@id="panels-ipe-regionid-' . $id . '"]';
    }

    /**
     * Waits until the drag and drop editor is displayed.
     */
    public function waitUntilEditorIsLoaded()
    {
        // Wait until the in-place editor is refreshed. First the page is
        // reloaded, then the initIPE ajax command is launched. The class
        // 'panels-ipe-editing' indicates that the editor is fully loaded.
        // @see DrupalPanelsIPE::initEditing()
        $this->webdriver->waitUntilElementIsDisplayed('//div[contains(@class, "panels-ipe-editing")]');
    }

    /**
     * {@inheritdoc}
     */
    protected function getCurrentRegions()
    {
        $regions = array();
        foreach ($this->layout->getRegions() as $id => $name) {
            $regions[$id] = new PanelsIPERegion($this->webdriver, $id, $name, $this->getRegionXPathSelector($id));
        }
        return $regions;
    }
}
