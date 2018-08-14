<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OutgoingRSS\Pane\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\App\OutgoingRSS\Pane;

use Kanooh\Paddle\Apps\OutgoingRSS;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOutgoingRSS\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Element\OutgoingRSS\RSSFeedSettingsModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\OutgoingRssPanelsContentType;

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
     * The id of the feed created for the test.
     *
     * @var string
     */
    protected $fid;

    /**
     * {@inheritDoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->configurePage = new ConfigurePage($this);

        $this->appService->enableApp(new OutgoingRSS);
    }

    /**
     * {@inheritDoc}
     */
    protected function additionalTestSetUp()
    {
        // Add an outgoing RSS entity.
        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonCreate->click();
        $modal = new RSSFeedSettingsModal($this);
        $modal->waitUntilOpened();

        $title = $this->alphanumericTestDataProvider->getValidValue();
        $modal->form->title->fill($title);
        $modal->form->basicPageCheckBox->check();
        $modal->form->saveButton->click();
        $this->configurePage->feedTable->waitUntilTableUpdated($title);
        $row = $this->configurePage->feedTable->getRowByTitle($title);

        $this->fid = $row->fid;
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new OutgoingRssPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        /* @var OutgoingRssPanelsContentType $content_type */
        $content_type->getForm()->rssFeeds[$this->fid]->check();
    }
}
