<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleReCaptcha\ConfigurePage\ConfigureForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleReCaptcha\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the Paddle ReCaptcha configuration form.
 *
 * @property Text $reCaptchaSiteKey
 * @property Text $reCaptchaSecretKey
 */
class ConfigureForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'reCaptchaSiteKey':
                return new Text($this->webdriver, $this->webdriver->byName('recaptcha_site_key'));
                break;
            case 'reCaptchaSecretKey':
                return new Text($this->webdriver, $this->webdriver->byName('recaptcha_secret_key'));
                break;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
