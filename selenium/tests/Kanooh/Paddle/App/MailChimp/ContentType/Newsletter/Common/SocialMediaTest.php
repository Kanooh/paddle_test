<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common\SocialMediaTest.
 */

namespace Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common;

use Kanooh\Paddle\Apps\MailChimp;
use Kanooh\Paddle\App\SocialMedia\ContentType\Base\SocialMediaTestBase;
use Kanooh\Paddle\Utilities\MailChimpService;

/**
 * SocialMediaTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SocialMediaTest extends SocialMediaTestBase
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

    /**
     * Tests that the social share is not shown in newsletters.
     */
    public function testSocialMediaWidget()
    {
        // Go to the configuration page.
        $this->configurationPage->go();

        // Verify that no share is shown.
        try {
            $this->configurationPage->configureForm->getContentTypeCheckboxByName('newsletter');
            $this->fail('The share widget should never be displayed in newsletters.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
        }
    }
}
