<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\NewsViewPage.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage;

/**
 * A news detail page in the frontend view.
 */
class NewsViewPage extends ViewPage
{
    /**
     * Checks if the fields are in the correct div on the front end page view.
     */
    public function assertLayoutMarkup()
    {
        $this->webdriver->byCssSelector('div.pane-news-info-banner');
        $this->webdriver->byCssSelector('div.field-name-field-paddle-featured-image');
        $this->webdriver->byCssSelector('div.field-type-text-with-summary');
    }
}
