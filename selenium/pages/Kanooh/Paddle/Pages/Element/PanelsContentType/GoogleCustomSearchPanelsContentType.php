<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\GoogleCustomSearchPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\Form\Form;

/**
 * The 'Google custom search' Panels content type.
 */
class GoogleCustomSearchPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'google_custom_search';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Google custom search';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Google custom search.';

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
        // @todo Implement.
    }
}
