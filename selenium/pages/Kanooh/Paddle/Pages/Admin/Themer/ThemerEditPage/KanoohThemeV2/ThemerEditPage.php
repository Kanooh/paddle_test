<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\KanoohThemeV2\ThemerEditPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\KanoohThemeV2;

use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\FooterSection;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The Themer Edit page class for the Kanooh theme v2.
 *
 * @property BodySection $body
 * @property FooterSection $footer
 * @property HeaderSection $header
 * @property BasicStylingSection $basicStyling
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSubmit
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSaveAs
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
            case 'body':
                return new BodySection($this->webdriver, $this->section('body'));
            case 'footer':
                return new FooterSection($this->webdriver, $this->section('footer'));
            case 'header':
                return new HeaderSection($this->webdriver, $this->section('header'));
            case 'basicStyling':
                return new BasicStylingSection($this->webdriver, $this->section('basic_styling'));
            case 'buttonSubmit':
                return $this->webdriver->byXPath('//form[@id="paddle-themer-theme-edit-form"]//input[@id="edit-submit"]');
            case 'buttonSaveAs':
                return $this->webdriver->byXPath('//form[@id="paddle-themer-theme-edit-form"]//input[@id="edit-submit-as"]');
        }
        return parent::__get($property);
    }

    /**
     * Get the section.
     *
     * @param string $title
     *   The name of the section.
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   Return the section.
     */
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
