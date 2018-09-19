<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\ImagePanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\PanelsContentType\Image\ConfigurationForm;
use Kanooh\Paddle\Utilities\ScaldService;

/**
 * The 'Image' Panels content type.
 */
class ImagePanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'add_image';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Add image';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add image.';

    /**
     * The image file field value.
     *
     * @var string
     *   The path to the image file.
     */
    public $image;

    /**
     * The URL type field value.
     *
     * @var string
     *   One of the following:
     *   - 'no_link': Do not link the image.
     *   - 'internal': An internal link.
     *   - 'external': An external link.
     */
    public $viewMode;

    /**
     * The internal URL field value.
     *
     * @var string
     */
    public $internalUrl;

    /**
     * The external URL field value.
     *
     * @var string
     */
    public $externalUrl;

    /**
     * XPath selector of the form element.
     */
    public $formElementXPathSelector = '//form[@id="paddle-panes-add-image-content-type-edit-form"]';

    /**
     * {@inheritdoc}
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        $form = $this->getForm($element);

        if ($this->image) {
            $imageField = $form->image;

            // If an image already exists, remove it before continuing.
            $imageField->clear();

            $imageField->selectButton->click();

            // Add a new atom to the library, and insert it. The insertAtom
            // method waits for the library to close so we don't have to wait
            // for anything.
            $scald_service = new ScaldService($this->webdriver);
            $atom_id = $scald_service->addImageToLibraryModal($this->image);
            $scald_service->insertAtom($atom_id);
        }

        $this->fillInSections();
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(Element $element = null)
    {
        $xpath_selector = !empty($element) ? $element->getXPathSelector() : '';

        $form_xpath = $xpath_selector . '//form[@id="paddle-panes-add-image-content-type-edit-form"]';

        // Wait until the form is fully loaded, otherwise the test might fail.
        $form_element = $this->webdriver->waitUntilElementIsDisplayed($form_xpath);

        return new ConfigurationForm($this->webdriver, $form_element);
    }
}
