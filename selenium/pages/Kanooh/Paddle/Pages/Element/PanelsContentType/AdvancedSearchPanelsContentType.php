<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\AdvancedSearchPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\PanelsContentType\AdvancedSearch\ConfigurationForm;

/**
 * The 'Advanced search field' Panels content type.
 */
class AdvancedSearchPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'advanced_search';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Add an advanced search field';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add an advanced search field.';

    /**
     * The node id.
     *
     * @var int
     *   The id of the advanced search page node to add.
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
                $this->webdriver->byId('paddle-advanced-search-advanced-search-content-type-edit-form')
            );
        }

        return $this->form;
    }
}
