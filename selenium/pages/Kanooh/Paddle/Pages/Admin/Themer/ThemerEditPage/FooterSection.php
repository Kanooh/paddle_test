<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\FooterSection.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Themer\FontPlugin;

/**
 * Class FooterSection.
 *
 * @package Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage
 *
 * @property RadioButton $noFooter
 * @property RadioButton $thinFooter
 * @property RadioButton $fatFooter
 * @property FooterStyleRadioButtons $footerStyleRadioButtons
 * @property FontPlugin $footerLinksLevel1
 * @property FontPlugin $footerLinksLevel2
 * @property FontPlugin $disclaimerLinks
 * @property FontPlugin $footerStyleDescriptions
 */
class FooterSection extends Section
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'noFooter':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byId('edit-footer-structure-sections-form-elements-footer-footer-style-no-footer')
                );
            case 'thinFooter':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byId('edit-footer-structure-sections-form-elements-footer-footer-style-thin-footer')
                );
            case 'fatFooter':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byId('edit-footer-structure-sections-form-elements-footer-footer-style-fat-footer')
                );
            case 'footerStyleRadioButtons':
                return new FooterStyleRadioButtons($this->webdriver, $this->webdriver->byId('edit-footer-structure-sections-form-elements-footer-footer-style'));
            case 'footerStyleDescriptions':
                return $this->webdriver->byId('paddle-footer-styles-descriptions');
            case 'footerLinksLevel1':
                return new FontPlugin(
                    $this->webdriver,
                    $this->element->byId('paddle-style-plugin-instance-footer-level-1-menu-items-font')
                );
            case 'footerLinksLevel2':
                return new FontPlugin(
                    $this->webdriver,
                    $this->element->byId('paddle-style-plugin-instance-footer-level-2-menu-items-font')
                );
            case 'disclaimerLinks':
                return new FontPlugin(
                    $this->webdriver,
                    $this->element->byId('paddle-style-plugin-instance-disclaimer-link-font')
                );
        }

        return parent::__get($name);
    }
}
