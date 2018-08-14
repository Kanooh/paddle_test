<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\IncomingRSS\Pane\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\App\IncomingRSS\Pane;

use Kanooh\Paddle\Apps\IncomingRSS;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleIncomingRSS\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Element\IncomingRSS\RSSFeedModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\IncomingRssPanelsContentType;

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
     * The title of the feed created for the test.
     *
     * @var string
     */
    protected $feedTitle;

    /**
     * {@inheritDoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->configurePage = new ConfigurePage($this);

        $this->appService->enableApp(new IncomingRSS);
    }

    /**
     * {@inheritDoc}
     */
    protected function additionalTestSetUp()
    {
        // Add an incoming RSS entity.
        $this->configurePage->go();
        $this->configurePage->checkArrival();

        $this->configurePage->contextualToolbar->buttonAdd->click();
        $modal = new RSSFeedModal($this);
        $modal->waitUntilOpened();

        $feed_title = $this->alphanumericTestDataProvider->getValidValue();
        $modal->form->title->fill($feed_title);
        $modal->form->url->fill('http://feeds.bbci.co.uk/news/rss.xml');
        $modal->form->saveButton->click();
        $modal->waitUntilClosed();
        $this->waitUntilTextIsPresent('RSS feed saved.');
        $this->waitUntilTextIsPresent($feed_title);

        $this->feedTitle = $feed_title;
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new IncomingRssPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        /* @var IncomingRssPanelsContentType $content_type */
        $content_type->getForm()->incomingRssFeeds->selectOptionByLabel($this->feedTitle);
    }
}
