<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage;

use Kanooh\Paddle\Pages\Element\Links\LinkNotPresentException;
use Kanooh\Paddle\Pages\Element\NodeMetadataSummary\NodeMetadataSummary;
use Kanooh\Paddle\Pages\Element\Links\ContentAdminMenuLinks;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * Administrative node view for a generic node.
 *
 * @todo When all our nodes are Panelized, this should extend PanelsDisplayPage
 *   instead of PaddlePage.
 *
 * @property ViewPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property NodeMetadataSummary $nodeSummary
 *   The node summary (metadata).
 * @property ContentAdminMenuLinks $adminMenuLinks
 *   The admin menu links.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $revisionsLink
 *   The revisions link.
 */
class ViewPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/content_manager/node/%/view';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ViewPageContextualToolbar($this->webdriver);
            case 'nodeSummary':
                return new NodeMetadataSummary($this->webdriver);
            case 'adminMenuLinks':
                return new ContentAdminMenuLinks($this->webdriver);
            case 'revisionsLink':
                return $this->getRevisionsLink();
        }
        return parent::__get($property);
    }

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $this->webdriver->waitUntilElementIsDisplayed(
            '//body[contains(@class, "page-admin-content-manager-node-view")]'
        );
    }

    /**
     * Gets the node id of the node in the administrative node view.
     *
     * @return string
     *   The node id.
     */
    public function getNodeIDFromUrl()
    {
        // Recover the nid from the url.
        $url_segments = explode('/', $this->webdriver->url());
        $nid = $url_segments[count($url_segments) - 2];

        return $nid;
    }

    /**
     * Returns the revision link.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *
     * @throws LinkNotPresentException
     *   Thrown when the revision link is not present.
     */
    protected function getRevisionsLink()
    {
        $xpath = '//div[@id="node-metadata"]//a[contains(@class, "btn-revisions")]';
        try {
            return $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
        } catch (\Exception $ex) {
            throw new LinkNotPresentException('Revisions');
        }
    }
}
