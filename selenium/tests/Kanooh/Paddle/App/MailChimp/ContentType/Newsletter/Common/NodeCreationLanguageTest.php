<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common\NodeCreationLanguageTest.
 */

namespace Kanooh\Paddle\App\MailChimp\ContentType\Newsletter\Common;

use Kanooh\Paddle\App\Multilingual\ContentType\Base\NodeCreationLanguageTestBase;
use Kanooh\Paddle\Apps\MailChimp;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateNewsletterModal;
use Kanooh\Paddle\Utilities\MailChimpService;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeCreationLanguageTest extends NodeCreationLanguageTestBase
{

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        // Set the MailChimp API key.
        new MailChimpService($this, getenv('mailchimp_api_key'));

        parent::setUpPage();

        $this->appService->enableApp(new MailChimp);
    }

    /**
     * {@inheritdoc}
     */
    public function getModalClassName()
    {
        return '\Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateNewsletterModal';
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'newsletter';
    }

    /**
     * {@inheritdoc}
     */
    public function fillInAddModalForm($modal)
    {
        /** @var CreateNewsletterModal $modal */
        $modal->title->fill($this->alphanumericTestDataProvider->getValidValue());
        $modal->listOne->select();
        $modal->fromName->fill($this->alphanumericTestDataProvider->getValidValue());
        // The email domain must be a valid one, so we use kanooh.be as it's
        // the domain we used for our registration.
        $modal->fromEmail->fill('developers@kanooh.be');
    }
}
