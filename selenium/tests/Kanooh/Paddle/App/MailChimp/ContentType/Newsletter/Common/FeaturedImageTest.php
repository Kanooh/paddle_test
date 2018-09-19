<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common\FeaturedImageTest.
 */

namespace Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common;

use Kanooh\Paddle\Apps\MailChimp;
use Kanooh\Paddle\Core\ContentType\Base\FeaturedImageTestBase;
use Kanooh\Paddle\Utilities\MailChimpService;

/**
 * FeaturedImageTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class FeaturedImageTest extends FeaturedImageTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Set the MailChimp API key.
        new MailChimpService($this, getenv('mailchimp_api_key'));

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
