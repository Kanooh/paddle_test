<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\SocialMediaIdentityPanelsContentTypeForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Select;

/**
 * Class representing the social media identity pane form.
 *
 * @property Select $identities
 *   The select to select an identity, keyed by identity id.
 */
class SocialMediaIdentityPanelsContentTypeForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'identities':
                return new Select($this->webdriver, $this->element->byName('social_media_identity'));
                break;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
