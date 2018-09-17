<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\News\NewsPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\News;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SectionedPanelsContentType;

/**
 * The 'News' Panels content type.
 */
class NewsPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'paddle_news';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Add news content';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add news content.';

    /**
     * The node to show.
     *
     * @var string
     *   The node ID.
     */
    public $node;

    /**
     * The form, specific to this panels content type.
     *
     * @var Form
     */
    private $form;

    /**
     * {@inheritdoc}
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        // Set the node.
        $this->getForm()->newsAutocompleteField->fill('node/' . $this->node);

        $this->fillInSections();
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(Element $element = null)
    {
        if (!isset($this->form)) {
            $this->form = new NewsPanelsContentTypeForm(
                $this->webdriver,
                $this->webdriver->byCssSelector('form#paddle-news-paddle-news-content-type-edit-form')
            );
        }

        return $this->form;
    }
}
