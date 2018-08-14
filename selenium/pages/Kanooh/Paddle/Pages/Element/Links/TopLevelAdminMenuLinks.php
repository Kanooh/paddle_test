<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Links\TopLevelAdminMenuLinks.
 */

namespace Kanooh\Paddle\Pages\Element\Links;

/**
 * The default top level admin menu links.
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
 */
class TopLevelAdminMenuLinks extends AdminMenuLinks
{

    /**
     * {@inheritdoc}
     */
    public function linkInfo()
    {
        return $this->getDefaultTopLevelLinks();
    }
}
