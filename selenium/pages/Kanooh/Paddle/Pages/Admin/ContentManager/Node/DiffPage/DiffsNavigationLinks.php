<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\DiffPage\DiffsNavigationLinks.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\DiffPage;

use Kanooh\Paddle\Pages\Element\Links\Links;

/**
 * Class representing the links to navigate between diffs.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkPrevious
 *   The link to go to the previous diff page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkNext
 *   The link to go to the next diff page.
 */
class DiffsNavigationLinks extends Links
{
    /**
     * The common xpath selector for the links.
     *
     * @var string
     */
    protected $xpathSelector = '//table[contains(@class, "diff")]';

    /**
     * {@inheritdoc}
     */
    public function linkInfo()
    {
        return array(
            'Previous' => array('xpath' => $this->xpathSelector . '//td[contains(@class, "diff-prevlink")]/a'),
            'Next' => array('xpath' => $this->xpathSelector . '//td[contains(@class, "diff-nextlink")]/a'),
        );
    }
}
