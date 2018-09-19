<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\News\News
 */

namespace Kanooh\Paddle\Pages\Element\Pane\News;

use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\News\NewsPanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class for a Panels pane with Ctools content type 'paddle_news'.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $image
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $date
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $title
 */
class News extends Pane
{

    /**
     * @var NewsPanelsContentType
     */
    public $contentType;

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, $uuid, $xpath_selector = '')
    {
        parent::__construct($webdriver, $uuid, $xpath_selector);
        $this->contentType = new NewsPanelsContentType($this->webdriver);
    }

    /**
     * {@inheritdoc}}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'image':
                $xpath = '//div[@class="news-item-spotlight"]//div[@class="spotlight-image"]/img';
                return $this->webdriver->byXPath($xpath);
                break;
            case 'date':
                $xpath = '//div[@class="news-item-spotlight"]//div[@class="info"]//div[@class="date"]';
                return $this->webdriver->byXPath($xpath);
                break;
            case 'title':
                $xpath = '//div[@class="news-item-spotlight"]//div[@class="info"]//h3[@class="title"]/a';
                return $this->webdriver->byXPath($xpath);
                break;
        }
    }
}
