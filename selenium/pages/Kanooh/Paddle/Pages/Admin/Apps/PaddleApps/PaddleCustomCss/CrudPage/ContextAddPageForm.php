<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomCss\CrudPage\ContextAddPageForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomCss\CrudPage;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Form\Select;

/**
 * Class representing the form for context add page.
 *
 * @property Text $name
 * @property Text $class
 * @property Select $conditions
 * @property Select $taxonomy
 * @property Select $reactions
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $save
 */
class ContextAddPageForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'name':
                return new Text($this->webdriver, $this->element->byName('name'));
                break;
            case 'conditions':
                return new Select($this->webdriver, $this->element->byName('conditions[selector]'));
                break;
            case 'reactions':
                return new Select($this->webdriver, $this->element->byName('reactions[selector]'));
                break;
            case 'taxonomy':
                return new Select($this->webdriver, $this->element->byName('conditions[plugins][node_taxonomy][values][]'));
                break;
            case 'class':
                return new Text($this->webdriver, $this->element->byName('reactions[plugins][theme_html][class]'));
                break;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
