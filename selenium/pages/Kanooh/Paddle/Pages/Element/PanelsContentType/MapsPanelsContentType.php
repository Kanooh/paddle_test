<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\MapsPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\PanelsContentType\Maps\ConfigurationForm;

/**
 * The 'Maps search field' Panels content type.
 */
class MapsPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'maps';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Add an maps search field';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add an maps search field.';

    /**
     * The node id.
     *
     * @var int
     *   The id of the maps page node to add.
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
    public function getForm()
    {
        if (!isset($this->form)) {
            $this->form = new ConfigurationForm(
                $this->webdriver,
                $this->webdriver->byId('paddle-maps-maps-search-content-type-edit-form')
            );
        }

        return $this->form;
    }
}
