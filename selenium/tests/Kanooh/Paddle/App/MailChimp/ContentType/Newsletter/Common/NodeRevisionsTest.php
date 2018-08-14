<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common\NodeRevisionsTest.
 */

namespace Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common;

use Kanooh\Paddle\Apps\MailChimp;
use Kanooh\Paddle\Core\ContentType\Base\NodeRevisionsTestBase;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\MailChimpService;

/**
 * Class NodeRevisionsTest
 * @package Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeRevisionsTest extends NodeRevisionsTestBase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        // Set the MailChimp API key.
        new MailChimpService($this, getenv('mailchimp_api_key'));

        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new MailChimp);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createNewsletterViaUI($title);
    }
}
