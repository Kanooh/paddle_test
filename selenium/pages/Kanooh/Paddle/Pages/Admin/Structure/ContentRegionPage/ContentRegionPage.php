<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentRegionPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage;

use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\Paddle\Pages\Element\Links\TopLevelAdminMenuLinks;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The configuration settings of the Paddle Content Region module.
 *
 * @property TopLevelAdminMenuLinks $adminMenuLinks
 *   The admin menu links.
 * @property ContentRegionPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property ContentRegionPageLinks $links
 *   The collection of links on the page.
 */
class ContentRegionPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/structure/content_region';

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver);
    }

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        // Waiting for the body element, as PaddlePage does, is too generic. So
        // let's wait for something that's unique for this page.
        $xpath = '//body[contains(@class, "page-admin-structure-content-region")]';
        $this->webdriver->waitUntilElementIsDisplayed($xpath);
    }


    /**
     * Returns the content types that support content regions.
     *
     * This information is retrieved from the page, so this only works when the
     * browser is on the page itself.
     *
     * The global type 'all_pages' is included in this list.
     *
     * @return array
     *   An array of content types.
     */
    public function getSupportedTypes()
    {
        $types = array('all_pages');

        // We need to retrieve the information from the page.
        $this->checkPath();

        // The content types can be found in a class on a wrapper div around the
        // form checkboxes.
        $xpath = '//fieldset[@id = "edit-settings"]//div[contains(@class, "region-content-type-wrapper")]';

        /* @var $element \PHPUnit_Extensions_Selenium2TestCase_Element */
        foreach ($this->webdriver->elements($this->webdriver->using('xpath')->value($xpath)) as $element) {
            $matches = array();
            preg_match('/edit-settings-content-type-([a-z-]*)-wrapper/', $element->attribute('id'), $matches);
            if ($matches[1]) {
                $types[] = str_replace('-', '_', $matches[1]);
            }
        }

        return $types;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'adminMenuLinks':
                return new TopLevelAdminMenuLinks($this->webdriver);
            case 'contextualToolbar':
                return new ContentRegionPageContextualToolbar($this->webdriver);
            case 'links':
                return new ContentRegionPageLinks($this->webdriver);
        }
        return parent::__get($property);
    }

    /**
     * Call this function to override the global settings for content regions.
     *
     * @param string $type
     *   The content type for which to override the global settings.
     *
     * @return ContentTypeOverrideRow
     *   The UI element for overriding the specific content type.
     */
    public function getOverride($type)
    {
        return new ContentTypeOverrideRow($this->webdriver, $type);
    }
}
