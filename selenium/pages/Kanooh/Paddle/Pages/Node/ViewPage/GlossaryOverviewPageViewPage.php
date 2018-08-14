<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\GlossaryOverviewPageViewPage.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage;

use Kanooh\Paddle\Pages\Element\Pane\GlossaryOverviewPane;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The glossary overview page in the frontend view.
 *
 * @property GlossaryOverviewPane $glossaryOverviewPane
 *   The pane showing the overview of news items.
 */
class GlossaryOverviewPageViewPage extends ViewPage
{
    /**
     * The node ID of the glossary overview page.
     *
     * @var int
     */
    protected $nid;

    /**
     * {@inheritdoc}
     */
    public function go($arguments = array())
    {
        // Set the node ID of this page as the path argument.
        parent::go($this->getNodeId());
    }

    /**
     * Returns the node ID of the glossary overview page.
     *
     * @return int
     */
    public function getNodeId()
    {
        // Retrieve the node ID from the variables if it is not yet known.
        if (empty($this->nid)) {
            $drupalService = new DrupalService();
            $drupalService->bootstrap($this->webdriver);
            $this->nid = variable_get('paddle_glossary_overview_page_nid');
        }

        return $this->nid;
    }

    public function __get($property)
    {
        switch ($property) {
            case 'glossaryOverviewPane':
                return new GlossaryOverviewPane($this->webdriver);
        }

        return parent::__get($property);
    }
}
