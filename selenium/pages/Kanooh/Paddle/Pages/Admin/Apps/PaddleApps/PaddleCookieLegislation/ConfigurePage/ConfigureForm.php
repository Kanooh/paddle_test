<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCookieLegislation\ConfigurePage\ConfigureForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCookieLegislation\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Wysiwyg\Wysiwyg;

use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;

/**
 * Class representing the Paddle Cookie Legislation configuration form.
 *
 * @property Text $agree
 * @property Text $disagree
 * @property Text $privacyPolicyLink
 * @property Checkbox $enable
 * @property Checkbox $privacyPolicyTarget
 * @property Wysiwyg $popupMessage
 */
class ConfigureForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'enable':
                return new Checkbox($this->webdriver, $this->webdriver->byId('edit-eu-cookie-compliance-popup-enabled'));
                break;
            case 'agree':
                return new Text($this->webdriver, $this->webdriver->byId('edit-eu-cookie-compliance-popup-agree-button-message'));
                break;
            case 'disagree':
                return new Text($this->webdriver, $this->webdriver->byId('edit-eu-cookie-compliance-popup-disagree-button-message'));
                break;
            case 'privacyPolicyLink':
                return new Text($this->webdriver, $this->webdriver->byId('edit-eu-cookie-compliance-popup-link'));
                break;
            case 'privacyPolicyTarget':
                return new Checkbox($this->webdriver, $this->webdriver->byId('edit-eu-cookie-compliance-popup-link-new-window'));
                break;
            // TODO: make popupMessage text area language independent.
            case 'popupMessage':
                return new Wysiwyg($this->webdriver, 'edit-eu-cookie-compliance-popup-info-value');
                break;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
