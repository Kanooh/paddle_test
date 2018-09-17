<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SocialIdentities\Pane\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\App\SocialIdentities\Pane;

use Kanooh\Paddle\Apps\SocialIdentities;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleSocialIdentities\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SocialMediaIdentityPanelsContentType;
use Kanooh\Paddle\Pages\Element\SocialIdentities\SocialIdentityModal;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ReferenceTrackerPaneSectionsTest extends ReferenceTrackerPaneSectionsTestBase
{

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * The name of the social identity created for the test.
     *
     * @var string
     */
    protected $identityName;

    /**
     * {@inheritDoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->configurePage = new ConfigurePage($this);

        $this->appService->enableApp(new SocialIdentities);
    }

    /**
     * {@inheritdoc}
     */
    protected function additionalTestSetUp()
    {
        // Create a social identity to use in the pane.
        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonCreateIdentity->click();

        $modal = new SocialIdentityModal($this);
        $modal->waitUntilOpened();

        $this->identityName = $this->alphanumericTestDataProvider->getValidValue();
        $modal->form->name->fill($this->identityName);
        $modal->form->saveButton->click();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();
    }


    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new SocialMediaIdentityPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        /* @var SocialMediaIdentityPanelsContentType $content_type */
        $content_type->getForm()->identities->selectOptionByLabel($this->identityName);
    }
}
