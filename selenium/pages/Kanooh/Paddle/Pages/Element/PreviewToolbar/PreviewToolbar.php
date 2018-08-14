<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PreviewToolbar\PreviewToolbar.
 */

namespace Kanooh\Paddle\Pages\Element\PreviewToolbar;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * The toolbar that shows up for authenticated users in the frontend.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $closeButton
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $adminButton
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $toolbarContent
 */
class PreviewToolbar extends Element
{

    protected $xpathSelector = '//div[@id="paddle-preview-toolbar"]';

    /**
     * @deprecated Use the magic property $this->closeButton instead.
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function closeButton()
    {
        $xpath = $this->xpathSelector .
                 '/div[@id="paddle-preview-toolbar-content"]//div[@id="paddle-preview-toolbar-close-btn"]/a';
        return $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
    }

    /**
     * Magic getter.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'closeButton':
                $xpath = $this->xpathSelector . '/div[@id="paddle-preview-toolbar-content"]//div[@id="paddle-preview-toolbar-close-btn"]/a';
                return $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
            case 'toolbarContent':
                $xpath = $this->xpathSelector . '/div[@id="paddle-preview-toolbar-content"]';
                return $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
            case 'adminButton':
                $xpath = $this->xpathSelector . '/div[@id="paddle-preview-toolbar-content"]/div[@id="paddle-preview-toolbar-admin-home"]/a';
                return $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
        }
        throw new \Exception("The property $property is undefined.");
    }
}
