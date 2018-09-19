<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\SignupFormPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * The 'Signup Form' Panels content type.
 */
class SignupFormPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'signup_form';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Signup form';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Display a Signup form.';


    /**
     * The signup form id.
     *
     * @var int
     *   The id of the signup form to add.
     */
    public $signup_id;

    /**
     * {@inheritdoc}
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        $form = $this->getForm($element);

        if ($this->signup_id) {
            $form->signupForms[$this->signup_id]->select();
        }
        $this->fillInSections();
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(Element $element = null)
    {
        if (!isset($this->form)) {
            $this->form = new SignupFormPanelsContentTypeForm(
                $this->webdriver,
                $this->webdriver->byCssSelector('form#paddle-mailchimp-signup-form-content-type-edit-form')
            );
        }

        return $this->form;
    }
}
