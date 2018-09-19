<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Pane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\Modal\EditPaneModal;
use Kanooh\Paddle\Pages\Element\Pane\PaneSectionTop;
use Kanooh\Paddle\Pages\Element\PanelsContentType\PanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for a Panels pane.
 *
 * @property PaneSection $bottomSection
 *   The bottom section of the pane.
 */
class Pane extends Element
{

    /**
     * The bottom section of the pane.
     *
     * @var PaneSection
     */
    public $bottomSection;

    /**
     * The pane uuid.
     *
     * @var string
     */
    protected $uuid;

    /**
     * The toolbar containing the edit buttons for the pane.
     *
     * This is not available in the frontend.
     *
     * @var PaneToolbar
     */
    public $toolbar;

    /**
     * The edit pane modal dialog.
     *
     * @var EditPaneModal
     */
    public $editPaneModal;

    /**
     * The top section of the pane.
     *
     * @var PaneSectionTop
     */
    public $topSection;

    /**
     * Constructs a Pane object.
     *
     * @param WebDriverTestCase $webdriver
     *   The web driver.
     * @param string $uuid
     *   The UUID of the pane. Optional, only needed if multiple instances of
     *   the same pane are tested.
     * @param string $xpath_selector
     *   The XPath selector for this pane. Optional, only needed if you need to
     *   access the PaneToolbar.
     */
    public function __construct(WebDriverTestCase $webdriver, $uuid = '', $xpath_selector = '')
    {
        parent::__construct($webdriver);

        $this->uuid = $uuid;
        $this->xpathSelector = !empty($xpath_selector) ? $xpath_selector : $this->getXPathSelectorByUuid();
        $this->toolbar = new PaneToolbar($webdriver, $this->xpathSelector);
        $this->editPaneModal = new EditPaneModal($webdriver);

        // Initialize the sections if they exists.
        foreach (array('top', 'bottom') as $section) {
            $section_variable = $section . 'Section';
            $this->$section_variable = null;
            $section_xpath = $this->xpathSelector . '//div[contains(@class, "pane-section-' . $section . '")]';
            try {
                $this->$section_variable = new PaneSectionTop($this->webdriver, $section_xpath);
            } catch (\Exception $e) {
                // Do nothing.
            }
        }
    }

    /**
     * Returns the Universally Unique IDentifier of the pane.
     *
     * @return string
     *   The UUID.
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Returns an XPath selector based on the uuid of the pane, usable on both
     * the front- and back-end.
     *
     * @return string
     *   XPath selector for the pane.
     */
    public function getXPathSelectorByUuid()
    {
        return '//div[@data-pane-uuid="' . $this->getUuid() . '"]';
    }

    /**
     * Deletes the pane.
     */
    public function delete()
    {
        $this->toolbar->checkButtons();

        // Cache the element before deleting it.
        $pane_element = $this->webdriver->byXPath($this->getXPathSelectorByUuid());

        $this->webdriver->moveto($this->toolbar->buttonDelete);
        $this->webdriver->clickOnceElementIsVisible($this->toolbar->buttonDelete);
        $this->webdriver->acceptAlert();

        // Wait until we can no longer interact with the pane.
        $webdriver = $this->webdriver;
        $callable = new SerializableClosure(
            function () use ($pane_element, $webdriver) {
                try {
                    $pane_element->click();
                } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }

    /**
     * Edits the pane.
     *
     * This does not wait for the edit pane modal to close after submitting the
     * form so validation errors can be checked.
     *
     * @param PanelsContentType $content_type
     *   The Panels content type containing the new configuration of the pane.
     */
    public function edit(PanelsContentType $content_type)
    {
        $this->toolbar->buttonEdit->click();
        $this->editPaneModal->waitUntilOpened();
        $content_type->fillInConfigurationForm();
        $this->editPaneModal->submit();
    }

    /**
     * Edits the pane and waits for it to reload.
     *
     * This does not work with invalid configuration, as the pane will not
     * reload if there are validation errors.
     *
     * Works like PaddlePage::waitUntilPageIsLoaded().
     *
     * @param PanelsContentType $content_type
     *   The Panels content type containing the new configuration of the pane.
     */
    public function editAndWaitUntilReloaded(PanelsContentType $content_type)
    {
        $pane = $this;
        $callable = new SerializableClosure(
            function () use ($pane, $content_type) {
                // Edit the pane configuration.
                $pane->edit($content_type);

                // Wait until the edit pane modal is closed.
                $pane->editPaneModal->waitUntilClosed();
            }
        );
        $this->executeAndWaitUntilReloaded($callable);
    }

    /**
     * Executes a custom callback passed, and waits for the pane to reload.
     *
     * @param $callback
     *   Function to call before waiting for the pane to reload.
     */
    public function executeAndWaitUntilReloaded($callback)
    {
        // Get the pane itself on the page.
        $pane_element = $this->webdriver->byXPath('//div[@data-pane-uuid="' . $this->getUuid() . '"]');

        // Execute the callback.
        call_user_func($callback);

        // Wait until we can no longer interact with the old pane. This means
        // that the pane has been replaced with an updated version.
        $webdriver = $this->webdriver;
        $callable = new SerializableClosure(
            function () use ($pane_element, $webdriver) {
                try {
                    $pane_element->click();
                } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }

    /**
     * Waits until the pane is loaded.
     */
    public function waitUntilPaneLoaded()
    {
        $webdriver = $this->webdriver;
        $pane = $this;
        $callable = new SerializableClosure(
            function () use ($pane, $webdriver) {
                try {
                    $webdriver->byXPath($pane->getXPathSelectorByUuid());
                    return true;
                } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }

    /**
     * Checks the subpalette of the pane.
     * Returns the subpalette number of the pane.
     *
     * @return int|bool
     *   The number of the subpalette if available, false otherwise.
     */
    public function getSubPaletteNumber()
    {
        // Get the pane itself on the page.
        $pane_element = $this->webdriver->byXPath('//div[@data-pane-uuid="' . $this->getUuid() . '"]');
        // Retrieve the classes.
        $classes = $pane_element->attribute('class');
        // Extract the subpalette number.
        $matches = array();
        preg_match('/\bpaddle-color-palettes-subpalette-(\d+)/i', $classes, $matches);

        return isset($matches[1]) ? $matches[1] : false;
    }
}
