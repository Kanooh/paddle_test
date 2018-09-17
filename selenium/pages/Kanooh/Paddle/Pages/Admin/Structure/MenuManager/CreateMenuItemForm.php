<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\MenuManager\CreateMenuItemForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\MenuManager;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Scald\ImageAtomField;

/**
 * Class representing the Create Menu Item form.
 *
 * @property ImageAtomField $backgroundImage
 * @property RadioButton $internalLinkRadioButton
 * @property RadioButton $externalLinkRadioButton
 * @property Text $internalLinkPath
 * @property Text $externalLinkPath
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element_Select $navigation
 * @property Text $title
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $description
 */
class CreateMenuItemForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        // @todo - replace the 'navigation' and 'description' elements with
        // FormField instances.
        switch ($name) {
            case 'backgroundImage':
                return new ImageAtomField($this->webdriver, $this->webdriver->byXPath('.//div/input[@name="menu_background"]/..'));
                break;
            case 'internalLinkRadioButton':
                return new RadioButton($this->webdriver, $this->webdriver->byId('edit-content-button-internal'));
                break;
            case 'externalLinkRadioButton':
                return new RadioButton($this->webdriver, $this->webdriver->byId('edit-content-button-external'));
                break;
            case 'internalLinkPath':
                return new Text($this->webdriver, $this->webdriver->byName('internal_link'));
                break;
            case 'externalLinkPath':
                return new Text($this->webdriver, $this->webdriver->byName('external_link'));
                break;
            case 'navigation':
                $element =
                  $this->element->element($this->element->using('xpath')->value('.//select[@name="navigation"]'));
                return $this->webdriver->select($element);
                break;
            case 'title':
                return new Text($this->webdriver, $this->webdriver->byName('link_title'));
                break;
            case 'description':
                $criteria = $this->element->using('xpath')->value('.//textarea[@name="description"]');
                return $this->element->element($criteria);
                break;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
