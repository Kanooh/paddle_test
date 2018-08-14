<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\Wysiwyg\Wysiwyg;

/**
 * The 'Custom content' Panels content type.
 */
class CustomContentPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'free_content';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Custom content';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add free content.';

    /**
     * The body text.
     *
     * @var string
     */
    public $body;

    /**
     * {@inheritdoc}
     *
     * @todo Refactor to use the Form class.
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        $this->body = $this->body ?: $this->random->name(32);
        $this->getForm()->body->waitUntilReady();
        $this->getForm()->body->setBodyText($this->body);

        $this->fillInSections();
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(Element $element = null)
    {
        if (!isset($this->form)) {
            $this->form = new CustomContentPanelsContentTypeForm(
                $this->webdriver,
                $this->webdriver->byCssSelector('.modal-content')
            );
        }

        return $this->form;
    }
}
