<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\ConfigureForm.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Wysiwyg\Wysiwyg;

/**
 * Class representing the configuration form of a formbuilder node.
 *
 * @property Text $submitButtonLabel
 * @property array $hiddenElements
 * @property Wysiwyg $confirmationMessage
 * @property Checkbox $shieldSubmissions
 */
class ConfigureForm extends Form
{
    /**
     * @var array
     *   List of elements that must be hidden in the form.
     */
    protected $hiddenElements = array(
        '//fieldset[@id="edit-role-control"]',
        '//fieldset[@id="webform-preview-fieldset"]',
        '//fieldset[@id="edit-advanced"]',
    );

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'submitButtonLabel':
                $xpath = '//fieldset[@id="edit-submission"]//input[@id="edit-submit-text"]';
                return new Text($this->webdriver, $this->webdriver->byXPath($xpath));
            case 'hiddenElements':
                return $this->hiddenElements;
            case 'confirmationMessage':
                return new Wysiwyg($this->webdriver, 'edit-confirmation-value');
            case 'shieldSubmissions':
                return new Checkbox($this->webdriver, $this->webdriver->byName('shield_submissions'));
                break;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
