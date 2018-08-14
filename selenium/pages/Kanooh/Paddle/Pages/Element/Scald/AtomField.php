<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Scald\AtomField.
 */

namespace Kanooh\Paddle\Pages\Element\Scald;

use Drupal\Component\Utility\Random;
use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Form\FormField;
use Kanooh\Paddle\Pages\Element\Scald\Image\AddOptionsModal;

/**
 * A form field representing an atom field.
 *
 * @property AtomFieldAtom[] $atoms
 *   List of all selected atoms.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $selectButton
 *   The button to select a scald atom.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $valueField
 *   The field containing the selected atom ID.
 */
class AtomField extends FormField
{
    /**
     * An XPath selector representing the selected atoms.
     *
     * @var string
     */
    protected $atomsXPathSelector = './/div[contains(@class, "selected-items")]/div';

    /**
     * An XPath selector representing the button to upload the file.
     *
     * @var string
     */
    protected $selectButtonXPathSelector = './/a[contains(@class, "add-button")]';

    /**
     * An XPath selector representing the hidden field containing the atom ID.
     *
     * @var string
     */
    protected $valueFieldXPathSelector = './/input[@type="hidden"]';

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
            case 'atoms':
                return $this->getAtoms();
            case 'selectButton':
                return $this->getSelectButton();
            case 'valueField':
                return $this->getValueField();
        }
    }

    /**
     * Returns the hidden field containing the selected atom ID.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The webdriver element for the value field.
     */
    protected function getValueField()
    {
        $criteria = $this->element->using('xpath')->value(
            $this->valueFieldXPathSelector
        );
        $element = $this->element->element($criteria);

        return $element;
    }

    /**
     * Returns the select button.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The webdriver element for the select button.
     */
    protected function getSelectButton()
    {
        $criteria = $this->element->using('xpath')->value(
            $this->selectButtonXPathSelector
        );
        $element = $this->element->element($criteria);

        return $element;
    }

    /**
     * Checks that the select button is visible or not.
     *
     * @return bool
     *   TRUE if visible, FALSE if hidden.
     */
    public function selectButtonVisible()
    {
        return $this->selectButton->displayed();
    }

    /**
     * Returns a list of selected atoms
     *
     * @return AtomFieldAtom[]
     *   Array of the selected atoms.
     */
    protected function getAtoms()
    {
        $criteria = $this->element->using('xpath')->value(
            $this->atomsXPathSelector
        );
        $elements = $this->element->elements($criteria);
        $atoms = array();

        foreach ($elements as $element) {
            $atoms[] = new AtomFieldAtom($this->webdriver, $element);
        }

        return $atoms;
    }

    /**
     * Selects a specific atom.
     *
     * @param int $atom_id
     *   Id of the atom to select.
     */
    public function selectAtom($atom_id)
    {
        $this->webdriver->clickOnceElementIsVisible($this->selectButton);

        $library_modal = new LibraryModal($this->webdriver);
        $library_modal->waitUntilOpened();
        $atom = $library_modal->library->getAtomById($atom_id);
        $this->webdriver->clickOnceElementIsVisible($atom->insertLink);
        $library_modal->waitUntilClosed();
    }

    /**
     * Selects multiple atoms.
     *
     * @param array $atom_ids
     *   Ids of the atoms to select.
     */
    public function selectAtoms($atom_ids)
    {
        foreach ($atom_ids as $atom_id) {
            $this->selectAtom($atom_id);
        }
    }

    /**
     * Clears the file field.
     */
    public function clear()
    {
        // Press the remove buttons if there's any selected atoms.
        foreach ($this->getAtoms() as $atom) {
            $atom->removeButton->click();
        }

        // Wait until the select button appears again.
        $atom_field = $this;
        $callable = new SerializableClosure(
            function () use ($atom_field) {
                if ($atom_field->selectButtonVisible()) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, 1000);
    }

    /**
     * Upload an image and click 'insert' to use it.
     *
     * @deprecated AssetCreationService should be used to create atoms, and
     *   the selectAtom method should be used to select an existing atom.
     *
     * @param string $alt_text
     *   Optional alternative text for the image. If omitted a random string
     *   will be used.
     */
    public function chooseImage($alt_text = null)
    {
        if (is_null($alt_text)) {
            $random = new Random();
            $alt_text = $random->name(10);
        }

        // Add a new image.
        $this->getSelectButton()->click();
        $library_modal = new LibraryModal($this->webdriver);
        $library_modal->waitUntilOpened();
        $library_modal->addAssetButton->click();

        $add_modal = new AddAtomModal($this->webdriver);
        $add_modal->waitUntilOpened();

        $local_image_path = dirname(__FILE__) . '/../../../../../../tests/Kanooh/Paddle/assets/sample_image.jpg';
        $image_path = $this->webdriver->file($local_image_path);
        $add_modal->form->fileList->uploadFiles($image_path);

        $options_modal = new AddOptionsModal($this->webdriver);
        $options_modal->waitUntilOpened();

        // Fill in the alt text.
        $options_modal->form->alternativeText->fill($alt_text);

        $options_modal->form->finishButton->click();

        // Reinitialize the library modal as it will have new unique IDs.
        $library_modal = new LibraryModal($this->webdriver);
        $library_modal->waitUntilOpened();

        // Insert the image into the pane.
        $item = $library_modal->library->items[0];
        $item->insertLink->click();
        $library_modal->waitUntilClosed();
    }
}
