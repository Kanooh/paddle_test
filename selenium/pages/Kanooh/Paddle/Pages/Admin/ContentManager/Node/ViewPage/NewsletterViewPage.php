<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\NewsletterViewPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage;

use Kanooh\Paddle\Pages\Element\Links\ContentAdminMenuLinks;

/**
 * The administrative node view of a newsletter node.
 *
 * @property NewsletterViewPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property ContentAdminMenuLinks $adminContentLinks
 *   The admin content links.
 */
class NewsletterViewPage extends ViewPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new NewsletterViewPageContextualToolbar($this->webdriver);
            case 'adminContentLinks':
                return new ContentAdminMenuLinks($this->webdriver);
        }
        return parent::__get($property);
    }
}
