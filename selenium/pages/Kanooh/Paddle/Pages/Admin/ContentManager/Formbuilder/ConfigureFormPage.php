<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\ConfigureFormPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The 'Configure form' page of the Paddle Formbuilder module.
 *
 * @property FormBuilderContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property ConfigureForm $form
 *   The configure form.
 */
class ConfigureFormPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/content_manager/node/%/configure_form';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new FormBuilderContextualToolbar($this->webdriver);
            case 'form':
                return new ConfigureForm(
                    $this->webdriver,
                    $this->webdriver->byId('paddle-formbuilder-configure-webform')
                );
        }
        return parent::__get($property);
    }
}
