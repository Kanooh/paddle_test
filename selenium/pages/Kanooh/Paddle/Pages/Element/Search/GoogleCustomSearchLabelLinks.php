<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Search\GoogleCustomSearchLabelLinks.
 */

namespace Kanooh\Paddle\Pages\Element\Search;

use Kanooh\Paddle\Pages\Element\Links\Links;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing the Labels links on the Google Custom Search page.
 */
class GoogleCustomSearchLabelLinks
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//body[contains(@class, "page-google-custom-search")]';

    /**
     * Constructs a GoogleCustomSearchLabelLinks object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     */
    public function __construct(WebDriverTestCase $webdriver = null)
    {
        $this->webdriver = $webdriver;
    }

    /**
     * Finds the Refinement Label links and returns them.
     *
     * @return array
     *   Array of \PHPUnit_Extensions_Selenium2TestCase_Element objects each a
     *   link to a refinement label.
     */
    public function getLabelLinks()
    {
        $links = array();
        $xpath = '//ul[contains(@class, "search-labels")]/li/a[contains(@class, "label-link")]';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($this->xpathSelector . $xpath));

        if (count($elements)) {
            return $elements;
        }

        return null;
    }
}
