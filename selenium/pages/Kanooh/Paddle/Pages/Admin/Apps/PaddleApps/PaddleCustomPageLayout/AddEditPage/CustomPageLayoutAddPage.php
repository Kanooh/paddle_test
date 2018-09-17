<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\\Admin\Apps\PaddleApps\PaddleCustomPageLayout\AddEditPage\CustomPageLayoutAddPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomPageLayout\AddEditPage;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * CustomPageLayoutAddPage class.
 *
 * @property CustomPageLayoutForm $form
 */
class CustomPageLayoutAddPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/structure/panels/layouts/add-responsive';

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
