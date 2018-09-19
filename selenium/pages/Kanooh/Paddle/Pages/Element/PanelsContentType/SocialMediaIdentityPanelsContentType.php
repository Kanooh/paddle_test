<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\SocialMediaIdentityPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * The 'Social media identity' Panels content type.
 */
class SocialMediaIdentityPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'social_media_identity';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Social media identity';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add a social media identity.';

    /**
     * The identity to show.
     *
     * @var string
     *   The identity ID.
     */
    public $identity;

    /**
     * {@inheritdoc}
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        // Set the identity.
        $this->getForm()->identities->selectOptionByValue($this->identity);
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(Element $element = null)
    {
        if (!isset($this->form)) {
            $this->form = new SocialMediaIdentityPanelsContentTypeForm(
                $this->webdriver,
                $this->webdriver->byCssSelector('form#paddle-social-identities-social-media-identity-content-type-edit-form')
            );
        }

        return $this->form;
    }
}
