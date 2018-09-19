<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common\NodeMetadataSummaryTest.
 */

namespace Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common;

use Kanooh\Paddle\Apps\MailChimp;
use Kanooh\Paddle\Core\ContentType\Base\NodeMetadataSummaryTestBase;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\MailChimpService;

/**
 * Class NodeMetadataSummaryTest
 * @package Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeMetadataSummaryTest extends NodeMetadataSummaryTestBase
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
}
