<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\TopNewsPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane;

use Kanooh\Paddle\Pages\Element\NewsItem\NewsItem;

/**
 * The top news pane on the news overview page.
 *
 * @property newsItem newsItem
 */
class TopNewsPane extends Pane
{

    public function __get($property)
    {
        switch ($property) {
            case 'newsItem':
                return new NewsItem($this->webdriver, $this->webdriver->byCss('.pane-top-news'));
        }
        throw new \Exception("The property $property is undefined.");
    }
}
