<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage\OverviewPageForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;

/**
 * Class representing the taxonomy overview page form.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $submit
 *   The submit button.
 */
class OverviewPageForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'submit':
                return $this->webdriver->byId('edit-submit');
        }
        throw new FormFieldNotDefinedException($name);
    }
}
