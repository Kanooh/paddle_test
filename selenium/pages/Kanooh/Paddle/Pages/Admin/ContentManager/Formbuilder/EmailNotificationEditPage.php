<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\EmailNotificationEditPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * Class representing the edit page for email notification settings on webform.
 *
 * @property FormBuilderContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 */
class EmailNotificationEditPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'node/%/webform/emails/%';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new FormBuilderContextualToolbar($this->webdriver);
        }
        return parent::__get($property);
    }
}
