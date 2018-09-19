<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\\Admin\Apps\PaddleApps\PaddleOpeningHours\AddEditPage\OpeningHourAddPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\AddEditPage;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * OpeningHourAddPage class.
 *
 * @property OpeningHourForm $form
 */
class OpeningHourAddPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/content/opening_hours_set/add';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'form':
                return new OpeningHourForm($this->webdriver, $this->webdriver->byId('opening-hours-set-form'));
        }

        return parent::__get($property);
    }
}
