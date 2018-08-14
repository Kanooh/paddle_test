<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\LandingPageViewPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage;

use Kanooh\Paddle\Pages\Element\Links\ContentAdminMenuLinks;

/**
 * The administrative node view of a landing page.
 *
 * @property LandingPageViewPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property ContentAdminMenuLinks $adminContentLinks
 *   The admin content links.
 */
class LandingPageViewPage extends ViewPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new LandingPageViewPageContextualToolbar($this->webdriver);
            case 'adminContentLinks':
                return new ContentAdminMenuLinks($this->webdriver);
        }
        return parent::__get($property);
    }

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $xpath = '//body[contains(concat(" ", normalize-space(@class), " "), " page-admin-content-manager-node-view ")]';
        $this->webdriver->waitUntilElementIsDisplayed($xpath);
    }
}
