<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\NewsletterPage.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

/**
 * Page to edit a newsletter.
 *
 * @property NewsletterForm $newsletterForm
 *   The edit newsletter form.
 */
class NewsletterPage extends EditPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'newsletterForm':
                return new NewsletterForm($this->webdriver, $this->webdriver->byId('newsletter-node-form'));
        }
        return parent::__get($property);
    }
}
