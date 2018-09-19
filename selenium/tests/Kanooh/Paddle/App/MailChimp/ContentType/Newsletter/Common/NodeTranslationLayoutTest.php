<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common\NodeTranslationLayoutTestBase.
 */

namespace Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common;

use Kanooh\Paddle\App\Multilingual\ContentType\Base\NodeTranslationLayoutTestBase;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateNewsletterModal;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\NewsletterLayoutPage;
use Kanooh\Paddle\Utilities\MailChimpService;

/**
 * {@inheritdoc}
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeTranslationLayoutTest extends NodeTranslationLayoutTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->layoutPage = new NewsletterLayoutPage($this);

        // Set the MailChimp API key.
        new MailChimpService($this, getenv('mailchimp_api_key'));
    }

    /**
     * {@inheritDoc}
     */
    public function setUpNode()
    {
        return $this->contentCreationService->createNewsletter();
    }

    /**
     * {@inheritDoc}
     */
    public function fillTranslationModal($title = null)
    {
        $title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $from_name = $this->alphanumericTestDataProvider->getValidValue();
        $from_email = 'developers@kanooh.be';

        // Fill the required fields.
        $newsletter_modal = new CreateNewsletterModal($this);
        $newsletter_modal->waitUntilOpened();
        $newsletter_modal->title->fill($title);
        $newsletter_modal->listOne->select();
        $newsletter_modal->fromName->fill($from_name);
        $newsletter_modal->fromEmail->fill($from_email);
        $newsletter_modal->submit();
        $newsletter_modal->waitUntilClosed();
    }
}
