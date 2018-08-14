<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentTypeOverrideRow.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage;

use Kanooh\WebDriver\WebDriverTestCase;
use Kanooh\Paddle\Pages\Element\Element;

/**
 * The configuration settings of the content regions for simple contact pages.
 */
class ContentTypeOverrideRow extends Element
{
    /**
     * The checkbox for the override settings.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public $checkbox;

    /**
     * The edit link for the override content regions.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public $editLink;

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, $content_type_name)
    {
        parent::__construct($webdriver);
        $content_type_name = str_replace('_', '-', $content_type_name);
        $this->xpathSelector = '//div[@id="edit-settings-content-type-' . $content_type_name . '-wrapper"]';
        $this->checkbox = $this->webdriver->element(
            $this->webdriver->using('xpath')->value($this->xpathSelector . '//input[@type="checkbox"]')
        );
        $this->editLink = $this->webdriver->element(
            $this->webdriver->using('xpath')->value($this->xpathSelector . '//a')
        );
    }

    /**
     * Enable the override by ensuring the checkbox is checked.
     */
    public function enable()
    {
        if (!$this->checkbox->selected()) {
            $this->checkbox->click();
        }
    }

    /**
     * Disable the override by ensuring the checkbox is unchecked.
     */
    public function disable()
    {
        if ($this->checkbox->selected()) {
            $this->checkbox->click();
        }
    }
}
