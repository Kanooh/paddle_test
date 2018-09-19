<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Product\ProductContactPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\Product;

use Kanooh\Paddle\Pages\Element\ElementNotPresentException;
use Kanooh\Paddle\Pages\Element\Pane\Pane;

/**
 * Class for the Product contact content type.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $topSectionText
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $bottomSectionText
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $title
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $street
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $postalCode
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $locality
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $email
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $website
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $phone
 */
class ProductContactPane extends Pane
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'topSectionText':
                return $this->getWebdriverElement()->byXPath('.//div[@class="pane-section-top" and text() = "Opening hours and contact"]');
            case 'title':
                return $this->getWebdriverElement()->byXPath('.//div[@class="pane-section-body"]//h2');
            case 'street':
                return $this->getWebdriverElement()->byXPath('.//div[@class="pane-section-body"]//div[@class="product-opening-hours"]//div[@class="thoroughfare"]');
            case 'postalCode':
                return $this->getWebdriverElement()->byXPath('.//div[@class="pane-section-body"]//div[@class="product-opening-hours"]//span[@class="postal-code"]');
            case 'locality':
                return $this->getWebdriverElement()->byXPath('.//div[@class="pane-section-body"]//div[@class="product-opening-hours"]//span[@class="locality"]');
            case 'email':
                return $this->getWebdriverElement()->byXPath('.//div[@class="pane-section-body"]//div[@class="product-opening-hours"]//div[@class="email"]/a');
            case 'website':
                return $this->getWebdriverElement()->byXPath('.//div[@class="pane-section-body"]//div[@class="product-opening-hours"]//div[@class="website"]/a');
            case 'phone':
                return $this->getWebdriverElement()->byXPath('.//div[@class="pane-section-body"]//div[@class="product-opening-hours"]//div[@class="phone"]');
        }

        throw new ElementNotPresentException($name);
    }
}
