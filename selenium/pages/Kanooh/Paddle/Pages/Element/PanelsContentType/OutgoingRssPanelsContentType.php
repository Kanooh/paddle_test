<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\OutgoingRssPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * The 'Outgoing RSS feeds list' Panels content type.
 */
class OutgoingRssPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'outgoing_rss_feeds_list';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Outgoing RSS feeds list';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add a list of Outgoing RSS feeds.';


    /**
     * The Outgoing RSS feed IDs.
     *
     * @var array
     *   Array with the entity IDs of the feed added to the pane.
     */
    public $feeds_ids;

    /**
     * {@inheritdoc}
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        $form = $this->getForm($element);

        if ($this->feeds_ids) {
            foreach ($this->feeds_ids as $id) {
                $form->rssFeeds[$id]->check();
            }
        }
        $this->fillInSections();
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(Element $element = null)
    {
        if (!isset($this->form)) {
            $this->form = new OutgoingRssPanelsContentTypeForm(
                $this->webdriver,
                $this->webdriver->byCssSelector('form#paddle-outgoing-rss-outgoing-rss-feeds-list-content-type-edit-form')
            );
        }

        return $this->form;
    }
}
