<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\DownloadListPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane;

use Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadListPanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for a Panels pane with Ctools content type 'Download List'.
 *
 * @property array $downloads
 *   List of downloads (titles) in the pane, keyed by url.
 */
class DownloadListPane extends Pane
{

    /**
     * The object for the pane content type.
     *
     * @var DownloadListPanelsContentType
     */
    public $contentType;

    /**
     * Constructs a DownloadListPane.
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
        $this->contentType = new DownloadListPanelsContentType($this->webdriver);
    }

    /**
     * Returns a list of downloads in the pane.
     *
     * @return array
     *   An array of downloads (titles), keyed by url.
     */
    protected function getDownloads()
    {
        $downloads = array();

        $xpath = $this->getXPathSelector() . '//ul[contains(@class, "listing")]/li/a';
        $criteria = $this->webdriver->using('xpath')->value($xpath);
        $elements = $this->webdriver->elements($criteria);

        /* @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
        foreach ($elements as $element) {
            $url = $element->attribute('href');
            $title = $element->text();
            $downloads[$url] = $title;
        }

        return $downloads;
    }

    /**
     * Magic getter.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'downloads':
                return $this->getDownloads();
                break;
        }
    }
}
