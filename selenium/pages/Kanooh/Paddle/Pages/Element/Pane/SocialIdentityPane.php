<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\SocialIdentityPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane;

use Kanooh\Paddle\Pages\Element\PanelsContentType\SocialMediaIdentityPanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for a Panels pane with Ctools content type 'Social Media Identity'.
 *
 * @property array $identities
 *   List of identities (titles) in the pane, keyed by url.
 */
class SocialIdentityPane extends Pane
{

    /**
     * The object for the pane content type.
     *
     * @var SocialMediaIdentityPanelsContentType
     */
    public $contentType;

    /**
     * Constructs a SocialIdentityPane.
     *
     * @param WebDriverTestCase $webdriver
     *   The webdriver object.
     * @param string $uuid
     *   The uuid of the pane.
     * @param string $pane_xpath_selector
     *   More general xpath selector for the pane.
     */
    public function __construct(WebDriverTestCase $webdriver, $uuid, $pane_xpath_selector)
    {
        parent::__construct($webdriver, $uuid, $pane_xpath_selector);
        $this->contentType = new SocialMediaIdentityPanelsContentType($this->webdriver);
    }

    /**
     * Returns a list of identities in the pane.
     *
     * @return array
     *   An array of identities (titles and icon classes), keyed by URL.
     */
    protected function getIdentities()
    {
        $identities = array();

        $xpath = $this->getXPathSelector() . '//ul[contains(@class, "listing")]/li/a';
        $criteria = $this->webdriver->using('xpath')->value($xpath);
        $elements = $this->webdriver->elements($criteria);

        /* @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
        foreach ($elements as $element) {
            $url = $element->attribute('href');
            $title = $element->text();
            $icons = $element->elements($element->using('xpath')->value('.//span[contains(@class, "icon")]/i'));
            $icon = '';
            if (count($icons)) {
                $classes = explode(' ', $icons[0]->attribute('class'));
                // The first class should be 'fa', the second is what we need.
                $icon = str_replace('fa-', '', $classes[1]);
            }
            $identities[$url] = array('title' => $title, 'icon' => $icon);
        }

        return $identities;
    }

    /**
     * Magic getter.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'identities':
                return $this->getIdentities();
                break;
        }
    }
}
