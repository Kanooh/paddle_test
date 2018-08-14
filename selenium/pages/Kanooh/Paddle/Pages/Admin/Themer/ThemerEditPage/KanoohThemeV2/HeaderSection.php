<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\KanoohThemeV2\HeaderSection.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\KanoohThemeV2;

use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\MenuStyleRadioButtons;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\Section;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\BrandingRadioButtons;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class for the Header section in the Paddle Themer form.
 *
 * @property MenuStyleRadioButtons $menuStyleOptions
 * @property BrandingRadioButtons $brandingOptions
 * @property Text $voHeaderToken
 * @property Text $voFooterToken
 */
class HeaderSection extends Section
{
    public function __get($name)
    {
        switch ($name) {
            case 'menuStyleOptions':
                return new MenuStyleRadioButtons($this->webdriver, $this->webdriver->byId('edit-header-menu-style-sections-form-elements-menu-style-menu-style'));
            case 'brandingOptions':
                $xpath = 'edit-header-branding-sections-form-elements-branding-global-header-vo-branding';
                return new BrandingRadioButtons($this->webdriver, $this->webdriver->byId($xpath));
            case 'voHeaderToken':
                $xpath = 'edit-header-branding-sections-form-elements-branding-global-header-global-vo-tokens-header';
                return new Text($this->webdriver, $this->webdriver->byId($xpath));
            case 'voFooterToken':
                $xpath = 'edit-header-branding-sections-form-elements-branding-global-header-global-vo-tokens-footer';
                return new Text($this->webdriver, $this->webdriver->byId($xpath));
        }

        return parent::__get($name);
    }
}
