<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\NewsOverviewPageViewPage.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage;

use Kanooh\Paddle\Pages\Element\Pane\NewsOverviewPane;
use Kanooh\Paddle\Pages\Element\Pane\TopNewsPane;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The news overview page in the frontend view.
 *
 * @property NewsOverviewPane $newsOverviewPane
 *   The pane showing the overview of news items.
 * @property TopNewsPane $topNewsPane
 *   The pane showing the most recent news item.
 */
class NewsOverviewPageViewPage extends ViewPage
{
    /**
     * The node ID of the news overview page.
     *
     * @var int
     */
    protected $nid;

    /**
     * Constructs a NewsOverviewPageViewPage object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver test case.
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver);
    }

    /**
     * {@inheritdoc}
     */
    public function go($arguments = array())
    {
        // Set the node ID of this page as the path argument.
        parent::go($this->getNodeId());
    }

    /**
     * Returns the node ID of the news overview page.
     *
     * @return int
     */
    public function getNodeId()
    {
        // Retrieve the node ID from the variables if it is not yet known.
        if (empty($this->nid)) {
            $drupalService = new DrupalService();
            $drupalService->bootstrap($this->webdriver);
            $this->nid = variable_get('paddle_news_overview_page_nid');
        }

        return $this->nid;
    }

    /**
     * Returns whether or not the top news pane is present.
     *
     * @return bool
     *   True if the top news pane is present, false if it isn't.
     */
    public function hasTopNewsPane()
    {
        $elements = $this->webdriver->elements($this->webdriver->using('css selector')->value('.pane-top-news'));

        return (bool) count($elements);
    }

    public function __get($property)
    {
        switch ($property) {
            case 'newsOverviewPane':
                return new NewsOverviewPane($this->webdriver);
            case 'topNewsPane':
                return new TopNewsPane($this->webdriver);
        }

        return parent::__get($property);
    }
}
