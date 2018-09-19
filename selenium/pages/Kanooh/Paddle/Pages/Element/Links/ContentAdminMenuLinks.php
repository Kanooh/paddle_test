<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Links\ContentAdminMenuLinks.
 */

namespace Kanooh\Paddle\Pages\Element\Links;

/**
 * The default top level admin content links.
 *
 * @todo This should adapt itself to the currently logged in user.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkDashboard
 *   A link to the "Dashboard" page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkStructure
 *   A link to the "Structure" page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkContent
 *   A link to the "Content" page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkPaddleStore
 *   A link to the "Paddle Store" page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkManageContent
 *   A link to the Content "Search" page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkAddContent
 *   A link to the "Add Content" page.
 */
class ContentAdminMenuLinks extends AdminMenuLinks
{

    /**
     * {@inheritdoc}
     */
    public function linkInfo()
    {
        $top_level_links = $this->getDefaultTopLevelLinks();
        $second_level_links = $this->getSecondLevelLinks();

        return array_merge($top_level_links, $second_level_links['Content']);
    }
}
