<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\EmbedWidgetPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * The 'Embed widget' Panels content type.
 */
class EmbedWidgetPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'embed_widget';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Embed widget';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add an embed widget.';

    /**
     * The widget to show.
     *
     * @var string
     *   The widget ID.
     */
    public $widget;

    /**
     * {@inheritdoc}
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        // Set the widget.
        $this->getForm()->widgets[$this->widget]->select();

        $this->fillInSections();
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(Element $element = null)
    {
        if (!isset($this->form)) {
            $this->form = new EmbedWidgetPanelsContentTypeForm(
                $this->webdriver,
                $this->webdriver->byCssSelector('form#paddle-embed-embed-widget-content-type-edit-form')
            );
        }

        return $this->form;
    }
}
