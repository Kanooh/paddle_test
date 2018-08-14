<?php

/**
 * @file
 * Contains
 *     \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMegaDropdown\ConfigurePage\EditPageLayoutMegaDropdownModal.
 */

namespace Kanooh\Paddle\Pages\Admin\PaddleMegaDropdown\EditPage;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class for the modal dialog used to create new mega dropdown entities.
 */
class EditPageLayoutMegaDropdownModal extends Modal
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
        $links_xpath = $this->xpathSelector . '//div[contains(@class, "layout-link")]';
        $links = $this->webdriver->elements($this->webdriver->using('xpath')->value($links_xpath));

        return $links;
    }

    public function waitUntilOpened()
    {
        $this->webdriver->waitUntilElementIsDisplayed('//div[@id="panels-layout-category-Paddle-Layouts"]');
    }
}
