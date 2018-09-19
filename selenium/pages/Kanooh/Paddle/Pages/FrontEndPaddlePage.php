<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\FrontEndPaddlePage.
 */

namespace Kanooh\Paddle\Pages;

use Kanooh\Paddle\Pages\Element\Breadcrumb\Breadcrumb;
use Kanooh\Paddle\Pages\Element\LanguageSwitcher\FrontendLanguageSwitcher;
use Kanooh\Paddle\Pages\Element\LanguageSwitcher\FrontendMobileLanguageSwitcher;
use Kanooh\Paddle\Pages\Element\PreviewToolbar\PreviewToolbar;
use Kanooh\Paddle\Pages\Element\SocialMedia\ShareWidget\ShareWidget;

/**
 * A page that is displayed in the frontend of the website.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $bodyContent
 *   The body content element.
 * @property Breadcrumb $breadcrumb
 *   The breadcrumb trail.
 * @property \Kanooh\Paddle\Pages\Element\PreviewToolbar\PreviewToolbar $previewToolbar
 *   The preview toolbar at the top of the page.
 * @property FrontEndPaddlePageSearchForm $searchForm
 *   The search form in the page header.
 * @property ShareWidget $shareWidget
 *   The share widget container.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $pageTitle
 *   The page title element.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $pageHeader
 *   The page header element.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $mobileMenuTrigger
 *   The button to open the mobile menu.
 * @property FrontendLanguageSwitcher $languageSwitcher
 *   The interface language switcher element.
 * @property FrontendMobileLanguageSwitcher $mobileLanguageSwitcher
 *   The mobile interface language switcher element.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $mobileSearchButton
 *   The button to open the search block on mobile viewport.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $menuItemCollapsed
 *   The collapsed li element of the vertical menu.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $menuItemNoChildren
 *   The vertical menu item that has no Children.
 */
abstract class FrontEndPaddlePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'bodyContent':
                return $this->webdriver->byClassName('content');
            case 'breadcrumb':
                return new Breadcrumb($this->webdriver);
            case 'previewToolbar':
                return new PreviewToolbar($this->webdriver);
            case 'searchForm':
                return new FrontEndPaddlePageSearchForm($this->webdriver, $this->webdriver->byId('search-api-page-search-form-search'));
            case 'shareWidget':
                $element = $this->webdriver->byXpath('//div[contains(@class, "paddle-social-media-share")]');
                return new ShareWidget($this->webdriver, $element);
            case 'pageTitle':
                $criteria = $this->webdriver->using('xpath')->value('//h1[@id="page-title"]');
                $elements = $this->webdriver->elements($criteria);
                if (count($elements)) {
                    return $elements[0];
                }
                return null;
            case 'pageHeader':
                $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value('//header'));
                if (count($elements)) {
                    return $elements[0];
                }
                return null;
            case 'mobileMenuTrigger':
                return $this->webdriver->byClassName('mobile-menu-trigger');
            case 'languageSwitcher':
                $element = $this->webdriver->byId('block-locale-language');
                return new FrontendLanguageSwitcher($this->webdriver, $element);
            case 'mobileLanguageSwitcher':
                $element = $this->webdriver->byClassName('mobile-language-switcher');
                return new FrontendMobileLanguageSwitcher($this->webdriver, $element);
            case 'mobileSearchButton':
                return $this->webdriver->byClassName('mobile-search-btn');
            case 'menuItemCollapsed':
                return $this->webdriver->byCss('#paddle-vertical-menu .menu-block-wrapper .menu li.collapsed a');
            case 'menuItemNoChildren':
                return $this->webdriver->byCss('#paddle-vertical-menu .menu-block-wrapper .menu li.leaf a');
        }
        return parent::__get($property);
    }
}
