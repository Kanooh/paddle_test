<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\PaneSectionTop.
 */

namespace Kanooh\Paddle\Pages\Element\Pane;

/**
 * Class representing the pane section "Top".
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $image
 *   The main top section image.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $icon
 *   The icon image for the top section.
 */
class PaneSectionTop extends PaneSection
{

    /**
     * Checks if the image with the passed filename is present in the pane section.
     *
     * @deprecated
     *
     * @param string $filename
     *   The filename of the image that is supposed to be in the pane section without file extension.
     *
     * @return bool
     *   True if the image was found, false otherwise.
     */
    public function checkImageDisplayed($filename)
    {
        if ($image = $this->getSectionImage()) {
            return strpos($image->attribute('src'), $filename) !== false;
        }

        return false;
    }

    /**
     * Tries to find an image in the section.
     *
     * @deprecated
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element|null
     *   The section image if its exists, null otherwise.
     */
    public function getSectionImage()
    {
        $elements = $this->webdriver->elements(
            $this->webdriver->using('xpath')->value($this->xpathSelector . '/img')
        );

        if (count($elements)) {
            return $elements[0];
        }

        return null;
    }

    /**
     * Tries to find the text of the section.
     *
     * @deprecated
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element|null
     *   The section text if its exists, null otherwise.
     */
    public function getText()
    {
        $elements = $this->webdriver->elements(
            $this->webdriver->using('xpath')->value($this->xpathSelector)
        );

        if (count($elements)) {
            return $elements[0]->text();
        }

        return null;
    }

    /**
     * @inheritdoc.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'image':
                try {
                    return $this->element->byXPath('./img[contains(@class, "top-section-image")]');
                } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                    return null;
                }
                break;
            case 'icon':
                try {
                    return $this->element->byXPath('.//div[contains(@class, "top-section-icon")]//img');
                } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                    return null;
                }
                break;
        }

        return parent::__get($name);
    }
}
