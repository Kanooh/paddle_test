<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\GoogleCustomSearchPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane;

use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\PanelsContentType\GoogleCustomSearchPanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for a Panels pane with Ctools content type 'Google custom search'.
 *
 * @property Text $searchField
 *   The search field.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $searchButton
 *   The search button to execute the search.
 */
class GoogleCustomSearchPane extends Pane
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[contains(@class, "pane-google-custom-search")]';

    /**
     * The toolbar containing the edit buttons for the pane.
     *
     * @var PaneToolbar
     */
    public $contentType;

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, $uuid, $xpath_selector)
    {
        parent::__construct($webdriver, $uuid, $xpath_selector);

        $this->contentType = new GoogleCustomSearchPanelsContentType($this->webdriver);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'searchField':
                return new Text($this->webdriver, $this->webdriver->byXPath($this->xpathSelector . '//input[@name="search_field"]'));
            case 'searchButton':
                return $this->webdriver->byXPath($this->xpathSelector . '//input[@type="submit"]');
        }
    }

    /**
     * Checks the placeholder in the search textfield.
     *
     * @param string $placeholder
     *   The placeholder string to check on.
     *
     * @return bool
     *   True if the text field with this placeholder was found, false otherwise.
     */
    public function checkPlaceholder($placeholder)
    {
        $criteria = $this->webdriver->using('xpath')->value('//form[@id="paddle-google-custom-search-form"]//input[@placeholder="' . $placeholder .  '"]');
        $elements = $this->webdriver->elements($criteria);
        if (count($elements) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Checks the search button label.
     *
     * @param string $label
     *   The search button label to check on.
     *
     * @return bool
     *   True if the button with this label was found, false otherwise.
     */
    public function checkSearchButtonLabel($label)
    {
        $criteria = $this->webdriver->using('xpath')->value('//form[@id="paddle-google-custom-search-form"]//input[@value="' . $label .  '"]');
        $elements = $this->webdriver->elements($criteria);
        if (count($elements) > 0) {
            return true;
        }
        return false;
    }
}
