<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SocialIdentities\TopSectionImagePresenceTest.
 */

namespace Kanooh\Paddle\App\SocialIdentities;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\SocialIdentities;
use Kanooh\Paddle\Core\Pane\Base\TopSectionImagePresenceTestBase;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleSocialIdentities\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SocialMediaIdentityPanelsContentType;
use Kanooh\Paddle\Pages\Element\SocialIdentities\SocialIdentityModal;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class TopSectionImagePresenceTest extends TopSectionImagePresenceTestBase
{
    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);

        $this->appService->enableApp(new SocialIdentities);
    }

    /**
     * {@inheritdoc}
     */
    public function createPaneWithTopImage($nid)
    {
        $this->configurePage->go();
        $name = $this->alphanumericTestDataProvider->getValidValue();
        $this->configurePage->contextualToolbar->buttonCreateIdentity->click();

        $modal = new SocialIdentityModal($this);
        $modal->waitUntilOpened();
        $modal->form->name->fill($name);

        $row = $modal->form->table->getRowByPosition(0);
        $row->url->fill('http://facebook.com/' . $this->alphanumericTestDataProvider->getValidValue());
        $row->title->fill('facebook');
        $modal->form->saveButton->click();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();

        // Add a Google Custom Search pane to the test node.
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();
        $content_type = new SocialMediaIdentityPanelsContentType($this);

        $callable = new SerializableClosure(
            function () use ($content_type, $name) {
                $content_type->getForm()->identities->selectOptionByLabel($name);
            }
        );
        $pane = $region->addPane($content_type, $callable);

        // Edit it to add top image to it.
        $this->addTopImageToPane($pane, $content_type);

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        return $pane->getUuid();
    }
}
