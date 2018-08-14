<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\PanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\WebDriver\WebDriverTestCase;

use Kanooh\Paddle\Pages\Element\PanelsContentType\Section\Top;
use Kanooh\Paddle\Pages\Element\PanelsContentType\Section\Bottom;
use Kanooh\Paddle\Pages\Element\PanelsContentType\Section\TopSectionForm;
use Kanooh\Paddle\Pages\Element\PanelsContentType\Section\BottomSectionForm;

/**
 * Base class for sectioned Panels content types.
 *
 * These are panes that have a 'Top' and 'Bottom' section.
 *
 * @property Top $top
 *   Deprecated. Use topSection instead.
 * @property Bottom $bottom
 *   Deprecated. Use bottomSection instead.
 * @property TopSectionForm $topSection
 *   The top section form.
 * @property BottomSectionForm $bottomSection
 *   The bottom section form.
 * @property Select|bool $titleOverride
 *   The drop-down added by Ctools to choose the Title override tag.
 */
abstract class SectionedPanelsContentType extends PanelsContentType
{

    /**
     * The value to set for the top section enable checkbox.
     *
     * @deprecated Use the $top property instead.
     *
     * @var bool
     */
    public $topSectionEnable;

    /**
     * The type of content to display in the top section.
     *
     * @var string
     *   Either 'text' or 'image'.
     *
     * @deprecated Use the $top property instead.
     */
    public $topSectionContentType;

    /**
     * @todo Document these properties.
     *
     * @deprecated Use the $top property instead.
     */
    public $topSectionText;
    public $topSectionImage;
    public $topSectionUrlType;
    public $topSectionInternalUrl;
    public $topSectionExternalUrl;

    /**
     * The value to set for the bottom section enable checkbox.
     *
     * @deprecated Use the $bottom property instead.
     *
     * @var bool
     */
    public $bottomSectionEnable;

    /**
     * @todo Document these properties.
     *
     * @deprecated Use the $bottom property instead.
     */
    public $bottomSectionText;
    public $bottomSectionUrlType;
    public $bottomSectionInternalUrl;
    public $bottomSectionExternalUrl;

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver);

        // @todo Remove this when the sections are populated.
        $this->bottomSectionEnable = false;
        $this->topSectionEnable = false;
    }

    /**
     * Magic getter.
     */
    public function __get($name)
    {
        switch ($name) {
            // @todo Replace all usage of 'Top' and 'Bottom' with the new
            //   TopSectionForm and BottomSectionForm.
            case 'top':
                return new Top($this->webdriver->byXPath('//fieldset[contains(@class, "pane-section-top")]'));
            case 'bottom':
                return new Bottom($this->webdriver->byXPath('//fieldset[contains(@class, "pane-section-bottom"]'));
            case 'topSection':
                return new TopSectionForm($this->webdriver, $this->webdriver->byXPath('//fieldset[contains(@class, "pane-section-top")]'));
            case 'bottomSection':
                return new BottomSectionForm($this->webdriver, $this->webdriver->byXPath('//fieldset[contains(@class, "pane-section-bottom")]'));
            case 'titleOverride':
                $xpath = '//select[@name="override_title_heading"]';
                $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
                return count($elements) ? new Select($this->webdriver, $elements[0]) : false;
        }
    }

    /**
     * Fills in the pane sections.
     *
     * @param Element $element
     *   (Optional) The parent element which contains the configuration form.
     *   For example a modal dialog or a block. This is used to build a specific
     *   xpath selector for the form elements.
     */
    public function fillInSections(Element $element = null)
    {
        $this->fillInTopSection($element);
        $this->fillInBottomSection($element);
    }

    /**
     * Fills in the top section.
     *
     * @todo Use the $topSection form instead of directly addressing the
     *   webdriver elements.
     *
     * @param Element $element
     *   (Optional) The parent element which contains the configuration form.
     *   For example a modal dialog or a block. This is used to build a specific
     *   xpath selector for the form elements.
     */
    public function fillInTopSection(Element $element = null)
    {
        $xpath_selector = !empty($element) ? $element->getXPathSelector() : '';

        // Set the top section status. By default this is enabled.
        $this->topSectionEnable = $this->topSectionEnable !== null ? $this->topSectionEnable : true;
        $xpath = $xpath_selector . '//input[@id="edit-top-enable-section"]';
        $this->webdriver->waitUntilElementIsPresent($xpath);
        $this->moveToAndSetCheckboxValue($xpath, $this->topSectionEnable);

        // If the section is disabled we're done here.
        if (!$this->topSectionEnable) {
            return;
        }

        // Fill in the content type. Choose a random type if none has been set.
        $this->topSectionContentType = $this->topSectionContentType ?: $this->getRandomTopSectionContentType();
        $xpath = $xpath_selector . '//input[@id="edit-top-section-wrapper-section-content-type-' .
            $this->topSectionContentType . '"]';
        $this->moveToAndClick($xpath);

        // Fill in the text. Default to some random text.
        $this->topSectionText = $this->topSectionText !== null ?
            $this->topSectionText : $this->random->name(8);
        $xpath = $xpath_selector . '//input[@id="edit-top-section-wrapper-section-text"]';
        $this->moveToAndSetValue($xpath, $this->topSectionText, true);

        // Fill in the image if the 'image' option was chosen. Default to a
        // random image.
        if ($this->topSectionContentType == 'image') {
            // If an image already exists, remove it before continuing.
            $xpath = $xpath_selector . '//input[@id = "edit-top-section-wrapper-section-image-remove-button"]';
            $element = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
            if (!empty($element)) {
                $this->moveToAndClick($xpath);
            }
            $this->webdriver->waitUntilElementIsPresent('//input[@name="files[top_section_wrapper_section_image]"]');

            $this->topSectionImage = $this->topSectionImage ?: $this->getRandomImage();
            $xpath = $xpath_selector . '//input[@id="edit-top-section-wrapper-section-image-upload"]';
            $file = $this->webdriver->file($this->topSectionImage);
            $this->moveToAndSetValue($xpath, $file);
        }

        // Set the link type. Choose a random one if none has been set.
        $this->topSectionUrlType = $this->topSectionUrlType ?: $this->getRandomUrlType();
        $xpath = $xpath_selector . '//input[@id="edit-top-section-wrapper-section-url-type-' .
            str_replace('_', '-', $this->topSectionUrlType) . '"]';
        $this->moveToAndClick($xpath);

        // Fill in the relevant link.
        switch ($this->topSectionUrlType) {
            case 'internal':
                $this->topSectionInternalUrl = $this->topSectionInternalUrl ?: $this->getRandomUrl();
                $xpath = $xpath_selector . '//input[@id="edit-top-section-wrapper-section-internal-url"]';
                $this->moveToAndSetValue($xpath, $this->topSectionInternalUrl, true);
                break;
            case 'external':
                $this->topSectionExternalUrl = $this->topSectionExternalUrl ?: $this->getRandomUrl();
                $xpath = $xpath_selector . '//input[@id="edit-top-section-wrapper-section-external-url"]';
                $this->moveToAndSetValue($xpath, $this->topSectionExternalUrl, true);
                break;
        }
    }

    /**
     * Fills in the bottom section.
     *
     * @todo Use the $bottomSection form instead of directly addressing the
     *   webdriver elements.
     *
     * @param Element $element
     *   (Optional) The parent element which contains the configuration form.
     *   For example a modal dialog or a block. This is used to build a specific
     *   xpath selector for the form elements.
     */
    public function fillInBottomSection(Element $element = null)
    {
        $xpath_selector = !empty($element) ? $element->getXPathSelector() : '';

        // Set the bottom section status. By default this is enabled.
        $this->bottomSectionEnable = $this->bottomSectionEnable !== null ? $this->bottomSectionEnable : true;
        $xpath = $xpath_selector . '//input[@id="edit-bottom-enable-section"]';
        $this->moveToAndSetCheckboxValue($xpath, $this->bottomSectionEnable);

        // If the section is disabled we're done here.
        if (!$this->bottomSectionEnable) {
            return;
        }

        // Fill in the text. Default to some random text.
        $this->bottomSectionText = $this->bottomSectionText ?: $this->random->name(8);
        $xpath = $xpath_selector . '//input[@id="edit-bottom-section-wrapper-section-text"]';
        $this->moveToAndSetValue($xpath, $this->bottomSectionText, true);

        // Set the link type. Choose a random one if none has been set.
        $this->bottomSectionUrlType = $this->bottomSectionUrlType ?: $this->getRandomUrlType();
        $xpath = $xpath_selector . '//input[@id="edit-bottom-section-wrapper-section-url-type-' .
            str_replace('_', '-', $this->bottomSectionUrlType) . '"]';
        $this->moveToAndClick($xpath);

        // Fill in the relevant link.
        switch ($this->bottomSectionUrlType) {
            case 'internal':
                $this->bottomSectionInternalUrl = $this->bottomSectionInternalUrl ?: $this->getRandomUrl();
                $xpath = $xpath_selector . '//input[@id="edit-bottom-section-wrapper-section-internal-url"]';
                $this->moveToAndSetValue($xpath, $this->bottomSectionInternalUrl, true);
                break;
            case 'external':
                $this->bottomSectionExternalUrl = $this->bottomSectionExternalUrl ?: $this->getRandomUrl();
                $xpath = $xpath_selector . '//input[@id="edit-bottom-section-wrapper-section-external-url"]';
                $this->moveToAndSetValue($xpath, $this->bottomSectionExternalUrl, true);
                break;
        }
    }

    /**
     * Returns a random top section content type.
     *
     * @deprecated Choosing random values needs to happen on another level,
     *   not in the page element classes themselves.
     *
     * @return string
     *   Either 'text' or 'image'.
     */
    protected function getRandomTopSectionContentType()
    {
        $types = array('text', 'image');
        $key = array_rand($types);
        return $types[$key];
    }

    /**
     * Returns a random link type.
     *
     *  @deprecated Choosing random values needs to happen on another level,
     *   not in the page element classes themselves.
     *
     * @return string
     *   Either 'no_link', 'internal' or 'external'.
     */
    protected function getRandomUrlType()
    {
        $types = array('no-link', 'internal', 'external');
        $key = array_rand($types);
        return $types[$key];
    }

    /**
     * Returns the path to a random image.
     *
     * @deprecated Choosing random values needs to happen on another level,
     *   not in the page element classes themselves.
     *
     * @return string
     *   An image path.
     */
    protected function getRandomImage()
    {
        // @todo This should return an actual random image from an asset folder
        //   and should be made available for the entire test suite.
        return getcwd() . '/tests/Kanooh/Paddle/assets/sample_image.jpg';
    }

    /**
     * Returns a random url.
     *
     * @deprecated Generating random values needs to happen on another level,
     *   not in the page element classes themselves.
     *
     * @return string
     *   A random url.
     */
    protected function getRandomUrl()
    {
        // Give random urls a fixed prefix and suffix to be sure they validate.
        return 'http://www.a' . strtolower($this->random->name(8)) . '.com/';
    }

    /**
     * Disables the pane sections.
     */
    public function disableSections()
    {
        // Disable the top section.
        if ($this->webdriver->byId('edit-top-enable-section')->selected()) {
            $this->webdriver->byId('edit-top-enable-section')->click();
        }

        // Disable the bottom section.
        if ($this->webdriver->byId('edit-bottom-enable-section')->selected()) {
            $this->webdriver->byId('edit-bottom-enable-section')->click();
        }
    }
}
