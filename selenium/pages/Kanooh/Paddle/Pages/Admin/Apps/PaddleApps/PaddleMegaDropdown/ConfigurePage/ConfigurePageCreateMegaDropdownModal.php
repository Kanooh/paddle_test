<?php

/**
 * @file
 * Contains
 *     \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMegaDropdown\ConfigurePage\ConfigurePageCreateMegaDropdownModal.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMegaDropdown\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class for the modal dialog used to create new mega dropdown entities.
 */
class ConfigurePageCreateMegaDropdownModal extends Modal
{
    /**
     * Retrieves and returns all layout links found in the modal.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element[]
     *   Array of \PHPUnit_Extensions_Selenium2TestCase_Element links keyed by
     *   layout machine name.
     */
    public function getLayoutLinks()
    {
        $layout_links = array();
        $links_xpath = $this->xpathSelector . '//div[contains(@class, "layout-link")]/a';
        $links = $this->webdriver->elements($this->webdriver->using('xpath')->value($links_xpath));
        foreach ($links as $element) {
            $layout_links[$element->attribute('title')] = $element;
        }

        return $layout_links;
    }
}
