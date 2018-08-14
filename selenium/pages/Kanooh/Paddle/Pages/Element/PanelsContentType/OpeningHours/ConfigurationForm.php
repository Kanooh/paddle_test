<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\OpeningHours\ConfigurationForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\OpeningHours;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ViewModeRadioButtons;
use Jeremeamia\SuperClosure\SerializableClosure;


/**
 * The 'Opening hours calendar' Panels content type edit form.
 *
 * @property ViewModeRadioButtons $viewModeRadios
 * @property AutoCompletedText $autocompleteField
 * @property OpeningHoursListNode[] $openingHoursListNode
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $addButton
 */
class ConfigurationForm extends Form
{

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'addButton':
                $criteria = $this->element->using('xpath')->value('.//input[@type="submit" and contains(@id, "edit-nodes-add")]');
                return $this->element->element($criteria);
                break;
            case 'viewModeRadios':
                return new ViewModeRadioButtons($this->webdriver, $this->element->byClassName('form-item-view-mode'));
            case 'autocompleteField':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byName('node'));
            case 'openingHoursListNode':
                $criteria = $this->element->using('xpath')->value('.//fieldset[contains(@id, "edit-nodes-list")]//div[contains(@class, "opening-hours-list-item")]');
                $elements = $this->element->elements($criteria);
                $openingHoursListNodes = array();
                foreach ($elements as $element) {
                    $element=  new OpeningHoursListNode($this->webdriver, $element);
                    $openingHoursListNodes[] = $element;
                }
                return $openingHoursListNodes;
                break;
        }

        throw new \Exception("Property with name $name not found");
    }

    /**
     * Removes a  node from the content type's configuration form.
     *
     * @param OpeningHoursListNode $openingHoursListNode
     *  A node object containing the node field and remove button.
     */
    public function removeNode($openingHoursListNode)
    {
        $form = $this->getForm();
        $current_amount = count($form->openingHoursListNode);

        $content_type = $this;

        $openingHoursListNode->remove->click();
        $callable = new SerializableClosure(
            function () use ($content_type, $current_amount) {
                // Make sure to always get a new instance of the form, as it may
                // have been rebuilt.
                $form = $content_type->getForm();
                if (count($form->openingHoursListNode) == $current_amount - 1) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        if (!isset($this->form)) {
            $this->form = new ConfigurationForm(
                $this->webdriver,
                $this->webdriver->byId('paddle-opening-hours-opening-hours-calendar-content-type-edit-form')
            );
        }

        return $this->form;
    }
}
