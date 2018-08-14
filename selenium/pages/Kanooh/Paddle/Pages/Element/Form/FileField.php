<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Form\FileField.
 */

namespace Kanooh\Paddle\Pages\Element\Form;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * A form field representing a file field.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $fileField
 *   The file field.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $removeButton
 *   The button to clear the upload field.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $uploadButton
 *   The button to initiate the file upload.
 */
class FileField extends FormField
{

    /**
     * An XPath selector representing the file upload field.
     *
     * @var string
     */
    protected $fileFieldXPathSelector;

    /**
     * An XPath selector representing the button to clear the field.
     *
     * @var string
     */
    protected $removeButtonXPathSelector;

    /**
     * An XPath selector representing the button to upload the file.
     *
     * @var string
     */
    protected $uploadButtonXPathSelector;

    /**
     * Constructs a FileField.
     *
     * The DOM elements representing a file field get replaced by AJAX calls
     * whenever the field is interacted with. This means we cannot rely on the
     * webdriver elements. Instead we accept XPath selectors for the different
     * elements we interact with: the file upload field, the file upload button,
     * and the button that allows to clear the field. These can then be used to
     * create webdriver elements on the fly.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $file_field_selector
     *   The XPath selector that represents the file upload field.
     * @param string $upload_button_selector
     *   The XPath selector that represents the button that initiates the file
     *   upload.
     * @param string $remove_button_selector
     *   The XPath selector that represents the button to clear the field.
     */
    public function __construct(WebDriverTestCase $webdriver, $file_field_selector, $upload_button_selector = '', $remove_button_selector = '')
    {
        $this->webdriver = $webdriver;
        $this->fileFieldXPathSelector = $file_field_selector;
        $this->uploadButtonXPathSelector = $upload_button_selector;
        $this->removeButtonXPathSelector = $remove_button_selector;
    }

    /**
     * Provides magic properties for the form elements.
     *
     * @param string $name
     *   The name of the form element.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The form element.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'fileField':
                return $this->getFileField();
            case 'uploadButton':
                return $this->getUploadButton();
            case 'removeButton':
                return $this->getRemoveButton();
        }
    }

    /**
     * Choose a file to upload.
     *
     * @param string $path
     *   The path to the file to upload.
     */
    public function chooseFile($path)
    {
        // If a file already exists, remove it before continuing.
        $this->clear();
        if ($field = $this->getFileField()) {
            $field->value($path);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getWebdriverElement()
    {
        // Returns either the file field or the remove button, depending on
        // which one is currently shown.
        $element = $this->getFileField();
        return $element ?: $this->getRemoveButton();
    }

    /**
     * Returns the file field if this is currently displayed.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The webdriver element for the file field.
     */
    protected function getFileField()
    {
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($this->fileFieldXPathSelector));
        return reset($elements);
    }

    /**
     * Returns the upload button if currently displayed.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The webdriver element for the upload button.
     */
    protected function getUploadButton()
    {
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($this->uploadButtonXPathSelector));
        return reset($elements);
    }

    /**
     * Returns the remove button if currently displayed.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The webdriver element for the remove button.
     */
    protected function getRemoveButton()
    {
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($this->removeButtonXPathSelector));
        return reset($elements);
    }

    /**
     * Clears the file field.
     */
    public function clear()
    {
        if ($button = $this->getRemoveButton()) {
            $this->webdriver->moveto($button);
            $button->click();

            $file_field = $this;

            // Wait until the upload button is visible again.
            $callable = new SerializableClosure(
                function () use ($file_field) {
                    if ($file_field->uploadButton && $file_field->uploadButton->displayed()) {
                        return true;
                    }
                }
            );
            $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        // Return true if either the file field or the remove button is enabled.
        $element = $this->getFileField();
        $element = $element ?: $this->getRemoveButton();
        return $element ? $element->enabled() : false;
    }

    /**
     * {@inheritdoc}
     */
    public function isDisplayed()
    {
        // Check if either the upload or the remove button is displayed.
        $element = $this->getUploadButton();
        $element = $element ?: $this->getRemoveButton();
        return $element ? $element->displayed() : false;
    }

    /**
     * {@inheritdoc}
     */
    public function waitUntilDisplayed()
    {
        // Wait until either the upload or the remove button is displayed.
        // For some reason we cannot check for the visibility of the file upload
        // field since our near sighted webdriver insists that the field is
        // never visible, not even if you rub its ugly little face in it. Lucky
        // for us it can see the buttons just fine.
        $webdriver = $this->webdriver;
        $upload_button_selector = $this->uploadButtonXPathSelector;
        $remove_button_selector = $this->removeButtonXPathSelector;
        $callable = new SerializableClosure(
            function () use ($webdriver, $upload_button_selector, $remove_button_selector) {
                $upload_button = $webdriver->elements($webdriver->using('xpath')->value($upload_button_selector));
                $upload_button = reset($upload_button);
                $remove_button = $webdriver->elements($webdriver->using('xpath')->value($remove_button_selector));
                $remove_button = reset($remove_button);
                return (!empty($upload_button) && $upload_button->displayed()) || (!empty($remove_button) && $remove_button->displayed()) ? true : null;
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }

    /**
     * Waits until the file is uploaded.
     */
    public function waitUntilFileUploaded()
    {
        // Wait until the remove button is visible. This indicates that the file
        // was successfully uploaded.
        $webdriver = $this->webdriver;
        $remove_button_selector = $this->removeButtonXPathSelector;
        $callable = new SerializableClosure(
            function () use ($webdriver, $remove_button_selector) {
                $remove_button = $webdriver->elements($webdriver->using('xpath')->value($remove_button_selector));
                $remove_button = reset($remove_button);
                return !empty($remove_button) && $remove_button->displayed() ? true : null;
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }

    /**
     * Returns the link to the uploaded file if available.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element|null
     */
    public function getFileLink()
    {
        if (!$this->getUploadButton() && $this->getRemoveButton()) {
            $xpath = $this->removeButtonXPathSelector . '/../span[contains(@class, "file")]/a';
            $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
            if (count($elements)) {
                return $elements[0];
            }
        }
        return null;
    }

    /**
     * Waits until the file has been removed.
     */
    public function waitUntilFileRemoved()
    {
        // Wait until the remove button is removed. This indicates that the file
        // is ready to be uploaded again.
        $webdriver = $this->webdriver;
        $upload_button_selector = $this->uploadButtonXPathSelector;
        $callable = new SerializableClosure(
            function () use ($webdriver, $upload_button_selector) {
                $upload_button = $webdriver->elements($webdriver->using('xpath')->value($upload_button_selector));
                $upload_button = reset($upload_button);
                return !empty($upload_button) && $upload_button->displayed() ? true : null;
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }
}
