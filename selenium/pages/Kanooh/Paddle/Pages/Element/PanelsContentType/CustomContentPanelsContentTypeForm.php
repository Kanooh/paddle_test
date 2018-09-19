<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentTypeForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Wysiwyg\Wysiwyg;
use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;

/**
 * Class representing the custom content pane form.
 *
 * @property Wysiwyg $body
 *   The body text in a wysiwyg.
 */
class CustomContentPanelsContentTypeForm extends Form
{

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'body':
                return new Wysiwyg($this->webdriver, 'edit-free-content-value');
                break;
            case 'autocompleteField':
              return new AutoCompletedText($this->webdriver, $this->webdriver->byName('top[section_wrapper][section_internal_url]'));
                break;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
