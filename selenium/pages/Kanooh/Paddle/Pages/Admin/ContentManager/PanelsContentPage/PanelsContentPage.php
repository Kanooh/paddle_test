<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage;

use Kanooh\Paddle\Pages\Element\Display\LandingPageDisplayPage;
use Kanooh\Paddle\Pages\Element\NodeMetadataSummary\NodeMetadataSummary;
use Kanooh\Paddle\Pages\Element\Pane\Pane;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

/**
 * The page that allows to edit the Panels display of a node.
 *
 * @property PanelsContentPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property NodeMetadataSummary $nodeSummary
 *   The node summary (metadata).
 */
class PanelsContentPage extends LandingPageDisplayPage
{

    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/content_manager/node/%/panels_content';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new PanelsContentPageContextualToolbar($this->webdriver);
            case 'nodeSummary':
                return new NodeMetadataSummary($this->webdriver);
        }
        return parent::__get($property);
    }

    /**
     * Saves current pane.
     *
     * @deprecated Will be replaced by a generic method on the Pane class.
     */
    public function savePane()
    {
        // Save the pane.
        $element = $this->webdriver->element(
            $this->webdriver->using('xpath')->value('//div[@id="add-pane-form"]//input[@name="op" and @type="submit"]')
        );
        $this->webdriver->moveto($element);
        $element->click();
        $this->waitUntilModalBackdropIsNotDisplayed();
    }

    /**
     * Update a pane.
     *
     * @deprecated Will be replaced by a generic method on the Pane class.
     */
    public function updatePane()
    {
        // update the pane.
        $element = $this->webdriver->element($this->webdriver->using('xpath')->value('//input[@id="edit-return"]'));
        $this->webdriver->moveto($element);
        $element->click();
        $this->waitUntilModalBackdropIsNotDisplayed();
    }

    /**
     * Changes the layout of a landing page.
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
        $save_button_xpath = '//input[@id="edit-submit"]';
        $this->webdriver->waitUntilElementIsDisplayed($save_button_xpath);
        $this->webdriver->keys(Keys::DOWN);
        $element = $this->webdriver->byXPath($save_button_xpath);
        $element->click();
        // Need to click twice to get into the modal.
        $element->click();

        $this->webdriver->waitUntilTextIsPresent('The layout has been changed.');
        $this->display->waitUntilEditorIsLoaded();

        // Re-initialize the display.
        $this->display;
    }
}
