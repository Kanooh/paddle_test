<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\\Admin\Apps\PaddleApps\PaddleCustomPageLayout\AddEditPage\CustomPageLayoutForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomPageLayout\AddEditPage;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * The main form of the add/edit custom page layouts.
 *
 * @property CustomPageLayoutAddEditPageContextualToolbar contextualToolbar
 * @property Text $title
 */
class CustomPageLayoutForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'contextualToolbar':
                return new CustomPageLayoutAddEditPageContextualToolbar($this->webdriver);
            case 'title':
                return new Text($this->webdriver, $this->element->byName('admin_title'));
                break;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
