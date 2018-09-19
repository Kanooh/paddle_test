<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common\ContentDiscoveryTest.
 */

namespace Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common;

use Kanooh\Paddle\Apps\MailChimp;
use Kanooh\Paddle\Core\ContentType\Base\ContentDiscoveryTestBase;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\NewsletterLayoutPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\MailChimpService;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ContentDiscoveryTest extends ContentDiscoveryTestBase
{
    /**
     * @var AppService
     */
    protected $appService;

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
    public function setUpPage()
    {
        parent::setUpPage();

        // Set the MailChimp API key.
        new MailChimpService($this, getenv('mailchimp_api_key'));

        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new MailChimp);
    }

    /**
     * {@inheritdoc}
     *
     * @return NewsletterLayoutPage
     */
    public function getLayoutPage()
    {
        return new NewsletterLayoutPage($this);
    }
}
