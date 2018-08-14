<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common\NodeShowBreadcrumbOptionTest.
 */

namespace Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common;

use Kanooh\Paddle\Core\ContentType\Base\NodeShowBreadcrumbOptionTestBase;
use Kanooh\Paddle\Utilities\MailChimpService;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeShowBreadcrumbOptionTest extends NodeShowBreadcrumbOptionTestBase
{

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        // Set the MailChimp API key.
        new MailChimpService($this, getenv('mailchimp_api_key'));

        parent::setUpPage();
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createNewsletterViaUI($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'newsletter';
    }
}
