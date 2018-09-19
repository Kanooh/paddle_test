<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\BuildFormPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder;

use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The 'Build form' page of the Paddle Formbuilder module.
 *
 * @property FormBuilderContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element[] $formElements
 *   The custom form elements added to the page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $radiosPaletteButton
 *   The button inside the elements palette to add a radio form element.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $textareaPaletteButton
 *   The button inside the elements palette to add a textarea form element.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $textfieldPaletteButton
 *   The button inside the elements palette to add a textfield form element.
 */
class BuildFormPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/content_manager/node/%/build_form';

    /**
     * {@inheritDoc}
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver);

        // Store a reference to the body element which we can use to see if the
        // page has been reloaded.
        $this->bodyElement = $webdriver->byXPath('//body');
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new FormBuilderContextualToolbar($this->webdriver);
            case 'formElements':
                $criteria = $this->webdriver->using('xpath')->value('//div[contains(@class, "form-builder-element")]');
                return $this->webdriver->elements($criteria);
        }

        // Match all the buttons available in the palette.
        if (preg_match('/(.+)PaletteButton$/', $property, $matches)) {
            $type = strtolower($matches[1]);
            return $this->webdriver->byXPath('//li[contains(@class, "field-' . $type . '")]/a');
        }

        return parent::__get($property);
    }

    /**
     * Drag and drop source to target.
     *
     * At the moment, this only works when both, source and target, are within
     * the viewport.
     *
     * @param string $field
     *   The name of the field.
     */
    public function dragAndDrop($field)
    {
        // Workaround: enlarge the viewport as much as possible.
        $this->webdriver->prepareSession()->currentWindow()->maximize();

        // Workaround: first move to target so there's more chance that the
        // target is visible when we want to move the source to it.
        $target = $this->webdriver->byXPath('//li[contains(@class, "field-' . $field . '")]');
        $this->webdriver->moveto($target);
        $this->webdriver->buttondown();

        // First go to the form container, otherwise the drop container is not
        // visible.
        $container = $this->webdriver->byXPath('//div[@id="form-builder"]');
        $this->webdriver->moveto($container);

        // Drop the field.
        $this->webdriver->waitUntilElementIsPresent('//div[contains(@class, "ui-droppable")]');
        $source = $this->webdriver->byXPath('//div[contains(@class, "ui-droppable")]');
        $this->webdriver->moveto($source);
        $this->webdriver->buttonup();
    }

    /**
     * Check if the custom form field is present.
     *
     * @param string $field
     *   The name of the field.
     *
     * @return bool
     *   TRUE if the filter field is present, FALSE otherwise.
     */
    public function checkCustomFormFieldPresent($field)
    {
        $xpath = '//div[@id="form-builder"]//input[@type="text"]';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        return (bool) count($elements);
    }

    /**
     * Set a title for the latest dragged form field.
     *
     * @param string $title
     *   The title to set in the field.
     */
    public function setHighlightedCustomFormFieldTitle($title)
    {
        // Get the wrapper element.
        $wrapper_xpath = '//div[contains(@class, "highlighted") and contains(@class, "form-builder-wrapper")]';
        $wrapper = $this->webdriver->byXPath($wrapper_xpath);

        // Click the element to open the settings.
        $clickable = $wrapper->byXPath('//div[contains(@class, "form-builder-clickable")]');
        $this->webdriver->moveto($clickable);
        $this->webdriver->click();

        // Wait for the label to be present. As the waitUntilElementIsDisplayed()
        // method wants a general xpath, get the form item id for easy targetting.
        $form_item = $wrapper->byXPath('//div[contains(@class, "form-builder-element")]');
        $form_item_id = $form_item->attribute('id');

        // Wait for the title field to be present.
        $title_field_xpath = '//div[@id="' . $form_item_id . '"]/..//input[@name="title"]';
        $this->webdriver->waitUntilElementIsPresent($title_field_xpath);
        $title_field = $this->webdriver->byXPath($title_field_xpath);

        // Set the field value and wait for the label to be updated.
        $title_field->clear();
        $title_field->value($title);
        $label_xpath = '//div[@id="' . $form_item_id . '"]//label[contains(text(), "' . $title . '")]';
        $this->webdriver->waitUntilElementIsPresent($label_xpath);
    }
}
