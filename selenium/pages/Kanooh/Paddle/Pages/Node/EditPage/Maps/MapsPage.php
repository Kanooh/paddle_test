<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\Maps\MapsPage.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Maps;

use Kanooh\Paddle\Pages\Node\EditPage\EditPage;

/**
 * Page to edit an maps search page.
 *
 * @property MapsSearchForm $mapsSearchForm
 */
class MapsPage extends EditPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'mapsSearchForm':
                return new MapsSearchForm(
                    $this->webdriver,
                    $this->webdriver->byClassName('node-paddle_maps_page-form')
                );
        }

        return parent::__get($property);
    }
}
