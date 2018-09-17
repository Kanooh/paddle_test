<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentRegionPageLinks.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage;

use Kanooh\Paddle\Pages\Element\Links\Links;

/**
 * The links for the configuration settings page of the content regions.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkEditContentForAllPages
 *   The link to the page that allows to edit content for all pages.
 */
class ContentRegionPageLinks extends Links
{

    /**
     * {@inheritdoc}
     */
    public function linkInfo()
    {
        // Defines all the links on the page.
        $links = array(
            'EditContentForAllPages' => array(
                'xpath' => '//div[@id="edit-all-pages"]/a[contains(@class, "paddle-content-region-edit-content-all_pages")]',
                'title' => 'Edit',
            ),
        );

        return $links;
    }
}
