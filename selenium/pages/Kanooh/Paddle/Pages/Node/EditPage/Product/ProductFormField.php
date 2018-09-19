<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\Product\ProductFormField.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Product;

use Kanooh\Paddle\Pages\Element\Form\FormField;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * The "form" field.
 *
 * @property Text $url
 * @property Text $title
 */
class ProductFormField extends FormField
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'url':
                return new Text($this->webdriver, $this->element->byName('field_paddle_prod_form[und][0][value]'));
            case 'title':
                return new Text($this->webdriver, $this->element->byName('field_paddle_prod_form[und][0][title]'));
        }
        throw new FormFieldNotDefinedException($name);
    }
}
