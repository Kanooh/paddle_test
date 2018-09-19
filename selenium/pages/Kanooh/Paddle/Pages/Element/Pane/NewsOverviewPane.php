<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\NewsOverviewPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane;

use Kanooh\Paddle\Pages\Element\NewsItem\NewsItem;
use Kanooh\Paddle\Pages\Element\Pager\Pager;

/**
 * The news overview pane on the news overview page.
 *
 * @property Pager $pager
 */
class NewsOverviewPane extends Pane
{
    /**
     * The maximum number of items in the overview pane.
     */
    const ITEMS_PER_PAGE = 9;

    public function __get($property)
    {
        switch ($property) {
            case 'pager':
                return new Pager($this->webdriver, $this->webdriver->byCss('.pane-news-overview-panel-pane-1 .pager'));
        }
        throw new \Exception("The property $property is undefined.");
    }

    /**
     * Returns the news items that are present on the overview.
     *
     * @return NewsItem[]
     */
    public function getNewsItems()
    {
        $news_items = array();

        foreach ($this->webdriver->elements($this->webdriver->using('css selector')->value('.pane-news-overview-panel-pane-1 .news-item')) as $news_item) {
            $news_items[] = new NewsItem($this->webdriver, $news_item);
        }

        return $this->orderNewsItems($news_items);
    }

    /**
     * Puts the news item array back in chronological order.
     *
     * The news items are displayed in three columns, and each subsequent item
     * is placed in the next column:
     *
     *   [0] [1] [2]
     *   [3] [4] [5]
     *   [6]
     *
     * Unfortunately they are represented in vertical column order in the HTML
     * document so when we request the items we get them in this order:
     *
     *   [0] [3] [6] [1] [4] [2] [5]
     *
     * We need to convert this back to the original order. This function works
     * as follows:
     *
     * - It traverses the array, starting with the first item, as this is always
     *   correct. This is removed from the array and placed as first item in the
     *   ordered array. We are left with:
     *
     *     [3] [6] [1] [4] [2] [5]
     *
     * - To get the next item [1] we now need to skip 2 items:
     *
     *     [3] (skip this) [6] (skip this) [1] <-- here it is!! [4] [2] [5]
     *
     * - Remove [1] from the array but keep track of where we are. We get this,
     *   with the asterisk marking our current position:
     *
     *     [3] [6] (*) [4] [2] [5]
     *
     * - To get to item [2] we need to skip 1 item from our current position.
     *   We can calculate the number of items to skip by taking the remaining
     *   number of items, dividing this by 3 and then rounding down. Following
     *   this logic this is what is left on the following steps:
     *
     *     [3] [6] [4] (*) [5] - now skip 1 to get to [3]
     *     (*) [6] [4] [5] - now skip 1 to get to [4]
     *     [6] (*) [5] - now skip 0 to get to [5]
     *
     * @param $array
     *   The array to order.
     *
     * @return array
     *   The ordered array.
     */
    protected function orderNewsItems($array)
    {
        $ordered_array = array();

        // Start with the first item.
        $offset = 0;

        while (count($array)) {
            // Start from the beginning when we pass the last item in the array.
            $offset %= count($array);

            // Remove the found item from the array.
            $ordered_array[] = $array[$offset];
            unset($array[$offset]);

            // Reindex the array so the keys are reset.
            $array = array_values($array);

            // Divide the remaining items by 3, round it down and add to our
            // current position.
            $offset += floor(count($array) / 3);
        }

        return $ordered_array;
    }

    /**
     * Returns the news item with the given index.
     *
     * @param int $index
     *   The numeric index of the item to return, starting with zero.
     *
     * @return NewsItem
     *   The requested news item.
     */
    public function getNewsItem($index)
    {
        return $this->getNewsItems()[$index];
    }

    /**
     * Returns whether or not a pager is present.
     *
     * @return bool
     *   True if the pane has a pager, false if it doesn't.
     */
    public function hasPager()
    {
        try {
            $this->pager;
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            return false;
        }

        return true;
    }
}
