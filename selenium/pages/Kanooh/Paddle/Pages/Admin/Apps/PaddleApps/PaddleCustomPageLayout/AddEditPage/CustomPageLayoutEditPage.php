<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\\Admin\Apps\PaddleApps\PaddleCustomPageLayout\AddEditPage\CustomPageLayoutEditPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomPageLayout\AddEditPage;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * CustomPageLayoutEditPage class.
 *
 * @property CustomPageLayoutForm $form
 */
class CustomPageLayoutEditPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/structure/panels/layouts/list/%/edit';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'form':
                return new CustomPageLayoutForm(
                    $this->webdriver,
                    $this->webdriver->byId('ctools-export-ui-edit-item-form')
                );
        }

        return parent::__get($property);
    }
}
