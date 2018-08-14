<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\\Admin\Apps\PaddleApps\PaddleOpeningHours\AddEditPage\OpeningHourEditPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\AddEditPage;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * OpeningHourEditPage class.
 *
 * @property OpeningHourForm $form
 */
class OpeningHourEditPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/content/opening_hours_set/edit/%';

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
