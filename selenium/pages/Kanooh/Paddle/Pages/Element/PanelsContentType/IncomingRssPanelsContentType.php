<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\IncomingRssPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * The 'Incoming RSS' Panels content type.
 */
class IncomingRssPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'incoming_rss';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Incoming RSS';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add an incoming RSS list.';

    /**
     * The Incoming RSS entity id.
     *
     * @var int
     *   The id of the Incoming RSS to add.
     */
    public $entity_id;

    /**
     * {@inheritdoc}
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        $form = $this->getForm($element);

        if ($this->entity_id) {
            $form->incomingRSSfeeds[$this->entity_id]->select();
        }
        $this->fillInSections();
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(Element $element = null)
    {
        $xpath = '//form[contains(@id, "paddle-incoming-rss-incoming-rss-content-type-edit-form")]';

        return new IncomingRssPanelsContentTypeForm($this->webdriver, $this->webdriver->byXPath($xpath));
    }
}
