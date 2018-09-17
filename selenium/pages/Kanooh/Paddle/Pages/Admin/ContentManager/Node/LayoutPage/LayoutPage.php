<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage;

use Kanooh\Paddle\Pages\Element\Display\ContentRegionDisplay;
use Kanooh\Paddle\Pages\Element\Display\PaddlePanelsDisplayPage;
use Kanooh\Paddle\Pages\Element\NodeMetadataSummary\NodeMetadataSummary;

/**
 * The Panels display editor for general content.
 *
 * @property \Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property \Kanooh\Paddle\Pages\Element\Display\PaddlePanelsDisplay $display
 *   The Panels display.
 * @property NodeMetadataSummary $nodeSummary
 *   The node summary (metadata).
 */
class LayoutPage extends PaddlePanelsDisplayPage
{

    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/content_manager/node/%/layout';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'display':
                return new ContentRegionDisplay($this->webdriver);
            case 'contextualToolbar':
                return new LayoutPageContextualToolbar($this->webdriver);
            case 'nodeSummary':
                return new NodeMetadataSummary($this->webdriver);
        }
        return parent::__get($property);
    }

    /**
     * Changes the layout of a page.
     *
     * @param string $layout
     *    The name of the layout to change to.
     */
    public function changeLayout($layout)
    {
        $this->contextualToolbar->buttonChangeLayout->click();
        $change_layout_xpath = '//div[contains(@class, "layout-link")]/div/a[@data-layout-name = "' . $layout . '"]';

        $this->webdriver->waitUntilElementIsDisplayed($change_layout_xpath);
        $element = $this->webdriver->element($this->webdriver->using('xpath')->value($change_layout_xpath));
        $element->click();

        // Last save the change.
        $save_button_xpath = '//form[@id="panels-change-layout"]//input[@value="Save"]';
        $this->webdriver->waitUntilElementIsDisplayed($save_button_xpath);
        $element = $this->webdriver->element($this->webdriver->using('xpath')->value($save_button_xpath));
        $element->click();

        $this->webdriver->waitUntilTextIsPresent('The layout has been changed.');
        $this->display->waitUntilEditorIsLoaded();

        // Re-initialize the display.
        $this->display;
    }
}
