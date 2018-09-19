<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\Cultuurnet.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage\Cultuurnet;

use Kanooh\Paddle\Pages\FrontEndPaddlePage;

/**
 * The class representing the Cultuurnet view page.
 */
class AgendaSearchPage extends FrontEndPaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'agenda/search';

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $this->webdriver->waitUntilElementIsDisplayed('//body[contains(@class, "page-agenda-search")]');
    }
}
