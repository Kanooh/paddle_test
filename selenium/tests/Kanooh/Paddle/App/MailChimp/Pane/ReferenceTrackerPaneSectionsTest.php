<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\MailChimp\Pane\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\App\MailChimp\Pane;

use Kanooh\Paddle\Apps\MailChimp;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SignupFormPanelsContentType;
use Kanooh\Paddle\Utilities\MailChimpService;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ReferenceTrackerPaneSectionsTest extends ReferenceTrackerPaneSectionsTestBase
{

    /**
     * @var MailChimpService
     */
    protected $mailChimpService;

    /**
     * @var int
     */
    protected $signupId;

    /**
     * {@inheritDoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->mailChimpService = new MailChimpService($this, getenv('mailchimp_api_key'));

        $this->appService->enableApp(new MailChimp);
    }

    /**
     * {@inheritDoc}
     */
    protected function additionalTestSetUp()
    {
        // Get the lists associated with this MailChimp account.
        $lists = $this->mailChimpService->getMailChimpLists();
        $list_names = array_values($lists);

        // Add new Signup form with one list only.
        $this->signupId = $this->mailChimpService->createSignupFormUI(
            $this->alphanumericTestDataProvider->getValidValue(8),
            array($list_names[0])
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new SignupFormPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        /* @var SignupFormPanelsContentType $content_type */
        $content_type->getForm()->signupForms[$this->signupId]->select();
    }
}
