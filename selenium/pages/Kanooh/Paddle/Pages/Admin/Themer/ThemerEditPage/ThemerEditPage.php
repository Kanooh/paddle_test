<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage;

use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The themer Edit page class.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSubmit
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSaveAs
 * @property BrandingSection $branding
 * @property BodySection $body
 * @property HeaderSection $header
 * @property FooterSection $footer
 * @property CustomCssSection $customCss
 * @property BrandingRadioButtons $brandingOptions
 * @property Text $voHeaderToken
 * @property Text $voFooterToken
 * @property \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPageContextualToolbar $contextualToolbar
 */
class ThemerEditPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/themes/%/edit';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'branding':
                return new BrandingSection($this->webdriver, $this->section('branding'));
            case 'body':
                return new BodySection($this->webdriver, $this->section('body'));
            case 'header':
                return new HeaderSection($this->webdriver, $this->section('header'));
            case 'footer':
                return new FooterSection($this->webdriver, $this->section('footer'));
            case 'customCss':
                return new CustomCssSection($this->webdriver, $this->section('custom_css'));
            case 'buttonSubmit':
                return $this->webdriver->byXPath('//form[@id="paddle-themer-theme-edit-form"]//input[@id="edit-submit"]');
            case 'buttonSaveAs':
                return $this->webdriver->byXPath('//form[@id="paddle-themer-theme-edit-form"]//input[@id="edit-submit-as"]');
            case 'brandingOptions':
                return new BrandingRadioButtons($this->webdriver, $this->webdriver->byId('edit-branding-form-elements-branding-global-header-vo-branding'));
            case 'voHeaderToken':
                return new Text($this->webdriver, $this->webdriver->byId('edit-branding-form-elements-branding-global-header-global-vo-tokens-header'));
            case 'voFooterToken':
                return new Text($this->webdriver, $this->webdriver->byId('edit-branding-form-elements-branding-global-header-global-vo-tokens-footer'));
            case 'contextualToolbar':
                return new ThemerEditPageContextualToolbar($this->webdriver);
        }
        return parent::__get($property);
    }

    protected function section($title)
    {
        $this->checkPath();
        $xpath = '//div/div[@id="paddle-themer-style-set-' . $title . '"]/..';
        return $this->webdriver->byXPath($xpath);
    }

    /**
     * Get the name of the theme from the url.
     */
    public function getThemeName()
    {
        $this->checkPath();
        $url_segments = explode('/', $this->webdriver->url());
        $name = $url_segments[count($url_segments) - 2];

        return $name;
    }
}
