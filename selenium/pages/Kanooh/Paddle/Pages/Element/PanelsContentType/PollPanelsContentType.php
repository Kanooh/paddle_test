<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\PollPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\PanelsContentType\Poll\ConfigurationForm;

/**
 * The 'Poll' Panels content type.
 */
class PollPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'poll';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Add a poll';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add a poll.';

    /**
     * The node id.
     *
     * @var int
     *   The id of the poll node to add.
     */
    public $nid;

    /**
     * {@inheritdoc}
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        $this->fillInSections();
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(Element $element = null)
    {
        if (!isset($this->form)) {
            $this->form = new ConfigurationForm(
                $this->webdriver,
                $this->webdriver->byId('paddle-poll-poll-content-type-edit-form')
            );
        }

        return $this->form;
    }
}
