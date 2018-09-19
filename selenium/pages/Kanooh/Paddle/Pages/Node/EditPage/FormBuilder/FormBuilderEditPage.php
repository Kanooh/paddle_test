<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\FormBuilder\FormBuilderEditPage.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\FormBuilder;

use Kanooh\Paddle\Pages\Node\EditPage\EditPage;

/**
 * Page to edit a form builder page "the node page not the form page ".
 *
 * @property FormBuilderEditForm $formBuilderEditForm
 */
class FormBuilderEditPage extends EditPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'formBuilderEditForm':
                return new FormBuilderEditForm($this->webdriver, $this->webdriver->byId('paddle-formbuilder-page-node-form'));
        }
        return parent::__get($property);
    }
}
