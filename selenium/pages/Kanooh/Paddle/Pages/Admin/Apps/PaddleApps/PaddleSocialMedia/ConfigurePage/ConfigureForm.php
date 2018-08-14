<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleSocialMedia\ConfigurePage\ConfigureForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleSocialMedia\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;

/**
 * Class representing the Paddle Social Media configuration form.
 */
class ConfigureForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        throw new FormFieldNotDefinedException($name);
    }

    /**
     * Retrieve the enable/disable checkbox for a content type.
     *
     * @param $name
     *   The content type machine name.
     *
     * @return \Kanooh\Paddle\Pages\Element\Form\Checkbox
     */
    public function getContentTypeCheckboxByName($name)
    {
        return new Checkbox($this->webdriver, $this->webdriver->byName("paddle_social_media_content_types[$name]"));
    }

    /**
     * Retrieve the enable/disable checkbox for a social.
     *
     * @param $name
     *   The social machine name.
     *
     * @return \Kanooh\Paddle\Pages\Element\Form\Checkbox
     */
    public function getSocialCheckboxByName($name)
    {
        return new Checkbox($this->webdriver, $this->webdriver->byName("paddle_social_media_networks[$name]"));
    }
}
