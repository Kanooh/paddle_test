<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomCss\CrudPage\ContextEditPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomCss\CrudPage;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The edit page for a context.
 *
 * @property ContextAddPageForm $form
 * @property ContextPageContextualToolbar $contextualToolbar
 */
class ContextEditPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/structure/context/list/%/edit';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ContextPageContextualToolbar($this->webdriver);
                break;
            case 'form':
                return new ContextAddPageForm($this->webdriver, $this->webdriver->byId('ctools-export-ui-edit-item-form'));
                break;
        }

        return parent::__get($property);
    }
}
