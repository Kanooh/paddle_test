<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Display\PanelsIPEDisplayPage.
 */

namespace Kanooh\Paddle\Pages\Element\Display;

/**
 * Base class for all Pages that use the Panels IPE display renderer.
 *
 * @property PanelsIPEDisplay $display
 *   The Panels display.
 */
abstract class PanelsIPEDisplayPage extends PanelsDisplayPage
{

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'display':
                return new PanelsIPEDisplay($this->webdriver);
        }
        return parent::__get($property);
    }

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        // Wait until the drag and drop editor is refreshed. First the page is
        // reloaded, then the initIPE ajax command is launched. The class
        // 'panels-ipe-editing' indicates that the editor is fully loaded.
        // @see DrupalPanelsIPE::initEditing()
        $this->webdriver->waitUntilElementIsDisplayed('//div[contains(@class, "panels-ipe-editing")]');
    }

    /**
     * Checks that the IPE is visible.
     */
    public function assertIPEVisible()
    {
        $ipe_display_xpath = '//div[contains(@class, "panels-ipe-display-container")]';
        $ipe_display = $this->webdriver->element($this->webdriver->using('xpath')->value($ipe_display_xpath));
        $this->webdriver->assertTrue($ipe_display->displayed());
    }
}
