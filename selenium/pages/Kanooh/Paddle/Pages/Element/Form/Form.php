<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Form\Form.
 */

namespace Kanooh\Paddle\Pages\Element\Form;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for a form.
 */
abstract class Form
{

    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * The Selenium webdriver element representing the form.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * The form build ID.
     *
     * @var string
     */
    protected $buildId;

    /**
     * The form ID.
     *
     * @var string
     */
    protected $id;

    /**
     * Constructs a new Form object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The Selenium webdriver element representing the form.
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
        $this->buildId = $this->getFormBuildId();
        $this->id = $this->element->attribute('id');
    }

    /**
     * Magically provides all known form fields as properties.
     *
     * @param string $name
     *   A field machine name.
     *
     * @return FormField
     *   The requested form field.
     *
     * @throws FormFieldNotDefinedException
     *   Thrown when the requested form field is not defined.
     */
    abstract public function __get($name);

    /**
     * Returns the message shown when the form is successfully submitted.
     *
     * @return string
     *   The confirmation message.
     */
    public function getSuccessMessage()
    {
        return '';
    }

    /**
     * Waits until the message indicating a successful form submission is shown.
     */
    public function waitForSuccessMessage()
    {
        if ($message = $this->getSuccessMessage()) {
            $this->webdriver->waitForText($message);
        }
    }

    /**
     * Get the value of the form_build_id input.
     *
     * @return string | null
     *   The form_build_id, null if not found.
     */
    public function getFormBuildId()
    {
        try {
            $criteria = $this->element->using('xpath')->value('.//input[@name="form_build_id"]');
            $input = $this->element->element($criteria);
            return $input->attribute('value');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            return null;
        }
    }

    /**
     * Makes the webdriver wait until the form build ID changes.
     *
     * This is useful to check if a form is refreshed using AJAX. When the form
     * is instantiated the form build ID is stored, and this method will
     * periodically poll the page until the form build ID changes. It will then
     * update the stored build ID with the new ID.
     *
     * Take care not to call this directly on a Form class that is provided as a
     * magic property on a Page class, since this will instantiate a new Form
     * with a new form build ID.
     *
     * Example usage:
     *
     * @code
     * // Instantiate the form and keep it in a local variable.
     * $form = $this->myPage->myForm;
     * // Do the AJAX magic.
     * $this->performSorcery();
     * // Wait until the form refreshes, using the locally cached .
     * $form->waitUntilFormBuildIdChanges();
     * @endcode
     *
     * This is wrong:
     *
     * @code
     * // Instantiate the form.
     * $this->myPage->myForm;
     * // Do the AJAX magic.
     * $this->performSorcery();
     * // This is wrong! myForm will be newly instantiated, the form build ID
     * // will be refreshed and it will wait forever.
     * $this->myPage->myForm->waitUntilFormBuildIdChanges();
     * @endcode
     *
     * @param string $current_form_build_id
     *   Optional current form build ID. The method will wait until this
     *   changes. If omitted the original form build ID from when the Form was
     *   instantiated will be used.
     */
    public function waitUntilFormBuildIdChanges($current_form_build_id = null)
    {
        // Default to the form build ID that was stored when the Form was
        // instantiated.
        $current_form_build_id = $current_form_build_id ?: $this->buildId;

        $form = $this;
        $callable = new SerializableClosure(
            function () use ($form, $current_form_build_id) {
                if ($form->hasFormBuildIdChanged($current_form_build_id)) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());

        $this->buildId = $this->getCurrentFormBuildId();
    }

    /**
     * Callback for self::waitUntilFormBuildIdChanges().
     *
     * Reports whether the form build id has changed. This method needs to be
     * public so Selenium2TestCase::waitUntil() can access it.
     *
     * @param string $current_form_build_id
     *   The original form build ID.
     *
     * @return boolean|null
     *   Returns null if the form build ID has not changed, true if it has.
     */
    public function hasFormBuildIdChanged($current_form_build_id)
    {
        if (!($new_form_build_id = $this->getCurrentFormBuildId()) || $current_form_build_id == $new_form_build_id) {
            return null;
        }
        return true;
    }

    /**
     * Get the value of the form_build_id input by form ID.
     *
     * Helper method for self::waitUntilFormBuildIdChanges().
     *
     * This is different from self::getFormBuildId() in that it targets a form
     * ID by XPath rather than relying on the DOM element that represents it.
     * This allows to get the form build id when a form is refreshed using AJAX,
     * as the original DOM element will have gone stale.
     * It also uses WebDriverTestCase::elements() rather than
     * WebDriverTestCase::element() to get instant results and avoid waiting on
     * an element that might have gone stale in the meanwhile.
     */
    protected function getCurrentFormBuildId()
    {
        // This is used to determine if the form has reloaded. If the element is
        // accessed on the exact moment the form is reloading it becomes stale
        // and an exception is thrown. Catch it and return NULL if this is the
        // case.
        try {
            /* @var $elements \PHPUnit_Extensions_Selenium2TestCase_Element */
            $xpath = '//form[@id="' . $this->id . '"]//input[@name="form_build_id"]';
            $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
            if (!empty($elements)) {
                return $elements[0]->attribute('value');
            }
            return null;
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            return null;
        }
    }
}
