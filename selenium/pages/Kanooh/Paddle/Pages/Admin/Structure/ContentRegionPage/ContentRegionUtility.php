<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentRegionUtility.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage;

use Kanooh\Paddle\Pages\Admin\ContentManager\Entity\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Element\Display\PaddlePanelsDisplay;
use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ContentRegionPanelsContentType;
use Kanooh\Paddle\Pages\Element\Links\StructureAdminMenuLinks;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Description of ContentRegionUtility
 *
 * @author Maarten
 */
class ContentRegionUtility
{
    /**
     * The content region panels page for all pages.
     *
     * @var PanelsContentPage
     */
    protected $contentRegionPanelsPage;

    /**
     * The content region configuration page.
     *
     * @var ContentRegionPage
     */
    protected $contentRegionConfigurationPage;

    /**
     * The admin bar.
     *
     * @var StructureAdminMenuLinks
     */
    protected $structureAdminMenuLinks;

    /**
     * @var WebDriverTestCase reference to webdriver object.
     */
    protected $webdriver;

    /**
     * Constructor.
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        $this->webdriver = $webdriver;
        $this->contentRegionPanelsPage = new PanelsContentPage($webdriver);
        $this->contentRegionConfigurationPage = new ContentRegionPage($webdriver);
        $this->structureAdminMenuLinks = new StructureAdminMenuLinks($webdriver);
    }


    /**
     * Adds custom content panes to the right and bottom regions.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $link
     *   The link to click to arrive on the wanted Panels display page.
     * @param string $right
     *  Text to place in the body field of the custom content pane that will be
     *   placed in the right region.
     * @param string $bottom
     *   Text to place in the body field of the custom content pane that will be
     *   placed in the bottom region.
     */
    public function addCustomContentPanes(\PHPUnit_Extensions_Selenium2TestCase_Element $link, $right, $bottom)
    {
        // Click the link.
        $link->click();

        // We should arrive on the content region panels page.
        $this->contentRegionPanelsPage->checkArrival();

        // Retrieve the regions from the display.
        $regions = $this->contentRegionPanelsPage->display->getRegions();

        // Instantiate the custom content pane type.
        $custom_content_pane = new CustomContentPanelsContentType($this->webdriver);

        // Add a custom content pane to the right region.
        $custom_content_pane->body = $right;
        $regions['right']->addPane($custom_content_pane);

        // Add a custom content pane to the bottom region.
        $custom_content_pane->body = $bottom;
        $regions['bottom']->addPane($custom_content_pane);

        // Save the page. We arrive on the content region configuration page.
        $this->contentRegionPanelsPage->contextualToolbar->buttonSave->click();
        $this->contentRegionConfigurationPage->checkArrival();
        $this->webdriver->assertTextPresent('The changes have been saved.');
    }

    /**
     * Adds a content region pane to a landing page.
     *
     * @param string $type
     *   The content region type. Example: 'all_pages' or 'basic_page'.
     * @param string $region
     *   Either 'right' or 'bottom'.
     * @param PaddlePanelsDisplay $display
     *   The display to add the pane to.
     *
     * @return Pane
     *   The new pane.
     */
    public function addContentRegionPane($type, $region, PaddlePanelsDisplay $display)
    {
        // Add the content region pane showing the requested region.
        $layout_region = $display->getRandomRegion();
        $content_region_pane = new ContentRegionPanelsContentType($this->webdriver);
        $content_region_pane->type = $type;
        $content_region_pane->region = $region;
        $pane = $layout_region->addPane($content_region_pane);

        return $pane;
    }
}
