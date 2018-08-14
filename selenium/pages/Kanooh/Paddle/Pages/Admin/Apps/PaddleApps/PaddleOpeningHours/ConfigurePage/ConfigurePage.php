<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\ConfigurePage;

use Kanooh\Paddle\Pages\Element\OpeningHours\OpeningHoursTable;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The configuration page for the opening hours paddlet.
 *
 * @property ConfigurePageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property OpeningHoursTable $openingHoursTable
 *   The table of opening hours.
 */
class ConfigurePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddlet_store/app/paddle_opening_hours/configure';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbar($this->webdriver);
            case 'openingHoursTable':
                return new OpeningHoursTable(
                    $this->webdriver,
                    '//form[@id="paddle-opening-hours-configuration-form"]//table/tbody'
                );
        }

        return parent::__get($property);
    }
}
