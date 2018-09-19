<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\EmailsFormPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The emails notification page of the Paddle Formbuilder module.
 *
 * @property EmailsFormPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property EmailsForm $form
 *   The configure form.
 */
class EmailsFormPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/content_manager/node/%/emails';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new EmailsFormPageContextualToolbar($this->webdriver);
            case 'form':
                return new EmailsForm(
                    $this->webdriver,
                    $this->webdriver->byId('paddle-formbuilder-webform-emails')
                );
        }
        return parent::__get($property);
    }
}
