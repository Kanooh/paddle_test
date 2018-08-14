<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage\OverviewPageCreateTermModal.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

use Kanooh\Paddle\Pages\Element\Modal\ModalFormElementNotDefinedException;
use Kanooh\Paddle\Pages\Element\Modal\ModalFormElementNotPresentException;

/**
 * Class for confirm modal dialogs.
 */
class OverviewPageCreateTermModal extends Modal
{
    /**
     * The Selenium objects for the term name element in the form found on the modal dialog.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element $formElementName
     */
    public $formElementName;

    /**
     * The Selenium objects for the term description element in the form found on the modal dialog.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element $formElementDescription
     */
    public $formElementDescription;

    /**
     * The Selenium objects for the term parent element in the form found on the modal dialog.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element $formElementParent
     */
    public $formElementParent;

    /**
     * Initializes the form elements in the modal dialog.
     *
     * @param array $form_elements
     *   The names of the form elements to initialize. These should be the name
     *   of the form elements as in the HTML. To initialize the name and the
     *   description pass "array('name', 'description')"
     *
     * @throws ModalFormElementNotDefinedException
     *   Thrown when the requested form element is not defined.
     *
     * @throws ModalFormElementNotPresentException
     *   Thrown when the requested form element is not present on the page.
     */
    public function initializeFormElements(array $form_elements = array())
    {
        $this->waitUntilOpened();
        // Initialize only the passed elements.
        foreach ($form_elements as $name) {
            $element = 'formElement' . ucfirst($name);
            if (!property_exists($this, $element)) {
                throw new ModalFormElementNotDefinedException($name);
            }
            try {
                $this->{$element} = $this->webdriver->byName($name);
            } catch (\Exception $e) {
                throw new ModalFormElementNotPresentException($name);
            }
        }
    }

    /**
     * Verifies that the name of the term is set as it is mandatory.
     *
     * @return bool
     *   True of the name is set, false otherwise.
     */
    public function termNameIsSet()
    {
        return $this->formElementName &&
            $this->formElementName instanceof \PHPUnit_Extensions_Selenium2TestCase_Element &&
            $this->formElementName->value();
    }
}
