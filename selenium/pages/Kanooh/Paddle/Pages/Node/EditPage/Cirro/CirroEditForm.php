<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\Cirro\CirroEditForm.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Cirro;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;

/**
 * Class representing the CIRRO edit form.
 *
 * @property AutoCompletedText $actionStrategies
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $actionStrategiesAddButton
 * @property AutoCompletedText $policyThemes
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $policyThemesAddButton
 * @property AutoCompletedText $settings
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $settingsAddButton
 */
class CirroEditForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'actionStrategies':
                return new AutoCompletedText($this->webdriver, $this->element->byName('field_paddle_cirro_action_strats[und][term_entry]'));
                break;
            case 'actionStrategiesAddButton':
                return $this->element->byXPath('.//div[contains(@class, "field-name-field-paddle-cirro-action-strats")]//input[@type = "submit" and @name = "op"]');
                break;
            case 'policyThemes':
                return new AutoCompletedText($this->webdriver, $this->element->byName('field_paddle_cirro_policy_themes[und][term_entry]'));
                break;
            case 'policyThemesAddButton':
                return $this->element->byXPath('.//div[contains(@class, "field-name-field-paddle-cirro-policy-themes")]//input[@type = "submit" and @name = "op"]');
                break;
            case 'settings':
                return new AutoCompletedText($this->webdriver, $this->element->byName('field_paddle_cirro_settings[und][term_entry]'));
                break;
            case 'settingsAddButton':
                return $this->element->byXPath('.//div[contains(@class, "field-name-field-paddle-cirro-settings")]//input[@type = "submit" and @name = "op"]');
                break;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
