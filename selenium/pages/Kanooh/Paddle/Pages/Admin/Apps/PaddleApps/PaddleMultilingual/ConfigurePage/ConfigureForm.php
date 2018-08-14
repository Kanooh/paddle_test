<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMultilingual\ConfigurePage\ConfigureForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMultilingual\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;

/**
 * Class representing the Paddle i18n configuration form.
 *
 * @property Checkbox $enableBulgarian
 * @property Checkbox $enableCzech
 * @property Checkbox $enableDutch
 * @property Checkbox $enableEnglish
 * @property Checkbox $enableFrench
 * @property Checkbox $enableGerman
 * @property Checkbox $enableIrish
 * @property RadioButton $defaultDutch
 * @property RadioButton $defaultEnglish
 * @property RadioButton $defaultFrench
 * @property RadioButton $defaultBulgarian
 */
class ConfigureForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        $class_name = '\Kanooh\Paddle\Pages\Element\Form\RadioButton';
        $language = '';
        $field_name = 'site_default';
        if (strpos($name, 'enable') === 0) {
            $language = substr($name, 6);
            $field_name = 'enabled[%code]';
            $class_name = '\Kanooh\Paddle\Pages\Element\Form\Checkbox';
        } elseif (strpos($name, 'default') === 0) {
            $language = substr($name, 7);
            $field_name = 'default';
        }
        $supported_languages = paddle_i18n_supported_languages();
        if ($language) {
            $lang_code = array_search($language, $supported_languages);
            $field_name = str_replace('%code', $lang_code, $field_name);
            $xpath = '//input[@name = "' . $field_name . '"][@value = "' . $lang_code . '"]';
            return new $class_name($this->webdriver, $this->webdriver->byXPath($xpath));
        }

        throw new FormFieldNotDefinedException($name);
    }
}
