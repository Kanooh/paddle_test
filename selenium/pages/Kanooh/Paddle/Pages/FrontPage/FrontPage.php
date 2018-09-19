<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\FrontPage\FrontPage.
 */

namespace Kanooh\Paddle\Pages\FrontPage;

use Kanooh\Paddle\Pages\FrontEndPaddlePage;
use Kanooh\Paddle\Pages\Element\MenuDisplay\FooterFrontEndMenuDisplay;
use Kanooh\Paddle\Pages\Element\MenuDisplay\MainFrontEndMenuDisplay;
use Kanooh\Paddle\Pages\Element\MenuDisplay\MainFrontEndVerticalMenuDisplay;
use Kanooh\Paddle\Pages\Element\Search\SearchBox;

/**
 * The class representing the homepage.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $logo
 *   The logo on the front-end.
 * @property FooterFrontEndMenuDisplay $footerMenuDisplay
 *   The footer menu's menu display on the front-end.
 * @property MainFrontEndMenuDisplay $mainMenuDisplay
 *   The main menu's menu display on the front-end.
 * @property SearchBox $searchBox
 *   The search box on the front end.
 * @property MainFrontEndVerticalMenuDisplay $mainMenuVerticalMenu
 *   The vertical navigation
 */
class FrontPage extends FrontEndPaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = '/';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'logo':
                return $this->webdriver->byXPath('//a[@id="logo"]/img');
            case 'mainMenuDisplay':
                return new MainFrontEndMenuDisplay($this->webdriver);
            case 'footerMenuDisplay':
                return new FooterFrontEndMenuDisplay($this->webdriver);
            case 'searchBox':
                return new SearchBox($this->webdriver);
            case 'mainMenuVerticalMenu':
                return new MainFrontEndVerticalMenuDisplay($this->webdriver);
        }

        return parent::__get($property);
    }

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $xpath = '//body[contains(concat(" ", normalize-space(@class), " "), " front ")]';
        $this->webdriver->waitUntilElementIsDisplayed($xpath);
    }

    /**
     * Check if the mega dropdown is shown in the front end.
     *
     * @return bool
     *   True if the mega dropdown is shown in the front end, false otherwise.
     */
    public function checkMegaDropdownMenuPresent()
    {
        $xpath = '//div[@class="paddle-mega-dropdown"]';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));

        return (bool)count($elements);
    }

    /**
     * Check if the fly-out menu is shown in the front end.
     *
     * @return bool
     *   True if the fly-out menu is shown in the front end, false otherwise.
     */
    public function checkFlyOutMenuPresent()
    {
        $xpath = '//ul[@class="paddle-sub-nav"]';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));

        return (bool)count($elements);
    }

    /**
     * Check if the expanded li is shown.
     *
     * @return bool
     *   True if the fly-out menu is shown in the front end, false otherwise.
     */
    public function checkVerticalMenuExpanded()
    {
        $xpath = '//li[@class="expanded"]';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));

        return (bool)count($elements);
    }
}
