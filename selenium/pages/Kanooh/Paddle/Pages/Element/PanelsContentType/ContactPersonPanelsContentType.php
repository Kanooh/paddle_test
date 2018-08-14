<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\ContactPersonPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\Form\Form;

/**
 * The 'Contact person' Panels content type.
 */
class ContactPersonPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'contact_person';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Contact person';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Contact person.';

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
        $this->getForm()->contactPersonAutocompleteField->fill('node/' . $this->node);

        $this->fillInSections();
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(Element $element = null)
    {
        if (!isset($this->form)) {
            $this->form = new ContactPersonPanelsContentTypeForm(
                $this->webdriver,
                $this->webdriver->byCssSelector('form#paddle-contact-person-content-type-edit-form')
            );
        }

        return $this->form;
    }
}
