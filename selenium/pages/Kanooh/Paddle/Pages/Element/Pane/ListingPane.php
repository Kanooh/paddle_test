<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\ListingPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane;

use Kanooh\Paddle\Pages\Element\PanelsContentType\ListingPanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for a Panels pane with Ctools content type 'Listing'.
 */
class ListingPane extends Pane
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[contains(@class, "pane-listing")]';

    /**
     * The toolbar containing the edit buttons for the pane.
     *
     * @var PaneToolbar
     */
    public $contentType;

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, $uuid, $xpath_selector = '')
    {
        parent::__construct($webdriver, $uuid, $xpath_selector);

        $this->contentType = new ListingPanelsContentType($this->webdriver);
    }

    /**
     * Determines if a node is present in a listing.
     *
     * @param int $nid
     *   The nid of the node we are looking for.
     *
     * @return bool
     *   True if the node was found, false otherwise.
     */
    public function nodeExistsInListing($nid)
    {
        $node_path = $this->xpathSelector . '//div[contains(@class, "node-' . $nid . '")]';

        return (bool) $this->getElementCountByXPath($node_path);
    }

    /**
     * Determines if a node is present in a listing in the admin or front end view.
     *
     * @param int $nid
     *   The nid of the node we are looking for.
     *
     * @return bool
     *   True if the node was found, false otherwise.
     */
    public function nodeExistsInAdminFrontViewListing($nid)
    {
        $node_path = '//div[contains(@class, "pane-listing")]//div[contains(@class, "node-' . $nid . '")]';

        return (bool) $this->getElementCountByXPath($node_path);
    }

    /**
     * Returns an array of all nodes in the listing.
     *
     * @return array
     *   Array of node titles, keyed by their nids.
     */
    public function getListedNodes()
    {
        $listed_nodes = array();
        $nodes_xpath = '//div[contains(@class, "pane-listing")]//div[contains(@class, "node")]';

        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element[] $nodes */
        $nodes = $this->webdriver->elements($this->webdriver->using('xpath')->value($nodes_xpath));

        foreach ($nodes as $node) {
            $class = $node->attribute('class');
            $regex = '/node-(\d+)/';
            $matches = array();
            preg_match($regex, $class, $matches);

            if (empty($matches)) {
                continue;
            }

            $nid = $matches[1];
            $title = $node->text();

            $listed_nodes[$nid] = $title;
        }

        return $listed_nodes;
    }

    /**
     * Checks if there are images present in the listing.
     *
     * @return bool
     *   True if images have been found, false otherwise.
     */
    public function checkImagesPresentInListing()
    {
        $criteria = $this->webdriver->using('xpath')->value($this->xpathSelector . '//img');
        $elements = $this->webdriver->elements($criteria);
        return (bool) count($elements);
    }
}
