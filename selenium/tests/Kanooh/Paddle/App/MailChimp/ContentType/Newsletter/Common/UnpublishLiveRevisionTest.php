<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common\UnpublishLiveRevisionTest.
 */

namespace Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common;

use Kanooh\Paddle\Core\ContentType\Base\UnpublishLiveRevisionTestBase;
use Kanooh\Paddle\Utilities\MailChimpService;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UnpublishLiveRevisionTest extends UnpublishLiveRevisionTestBase
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
        return $this->contentCreationService->createNewsletter($title);
    }
}
