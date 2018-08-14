<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Search\AdvancedSearchPagerLinks.
 */

namespace Kanooh\Paddle\Pages\Element\Search;

use Kanooh\Paddle\Pages\Element\Links\Links;

/**
 * Class representing the pager links.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkPrevious
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkNext
 */
class AdvancedSearchPagerLinks extends Links
{
    /**
     * {@inheritdoc}
     */
    public function linkInfo()
    {
        return array(
            'Previous' => array(
                'xpath' => './/li[contains(@class, "pager-previous")]/a',
            ),
            'Next' => array(
                'xpath' => './/li[contains(@class, "pager-next")]/a',
            ),
        );
    }
}
