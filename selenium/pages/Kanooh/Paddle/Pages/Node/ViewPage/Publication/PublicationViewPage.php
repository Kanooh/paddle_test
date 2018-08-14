<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\Publication\PublicationViewPage.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage\Publication;

use Kanooh\Paddle\Pages\Element\Pane\Publication\PublicationLeadImagePane;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;

/**
 * Class representing a Publication page in the frontend view.
 *
 * @property PublicationLeadImagePane $leadImagePane
 */
class PublicationViewPage extends ViewPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'leadImagePane':
                $element = $this->webdriver->byCssSelector('.pane-publication-lead-image');
                return new PublicationLeadImagePane($this->webdriver, $element->attribute('data-pane-uuid'));
        }
        return parent::__get($property);
    }
}
