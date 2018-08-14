<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\NodeContentPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * The 'Node content' Panels content type.
 */
class NodeContentPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'node_content';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Add page content';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add page content.';

    /**
     * The node to show.
     *
     * @var string
     *   The node, in the format 'XX (node/YY)', where 'XX' is supposed to be
     *   the node title (but in reality can be any string, such as 'What has it
     *   in its nasty little pocketses, my precious?'), and 'YY' is the node id.
     */
    public $node;

    /**
     * The view mode.
     *
     * @var int
     *   One of the following:
     *   - 0: Use the 'Summary' view mode.
     *   - 1: Use the 'Full' view mode.
     */
    public $viewMode;

    /**
     * The type of content to display in the top section.
     *
     * @var string
     *   Either 'title', 'text' or 'image'.
     */
    public $topSectionContentType;

    /**
     * {@inheritdoc}
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        $this->getForm()->nodeContentAutocomplete->fill($this->node);
        $this->getForm()->contentTypeRadios->select(rand(0, 1));

        $this->fillInSections();
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        if (!isset($this->form)) {
            $this->form = new NodeContentPanelsContentTypeForm(
                $this->webdriver,
                $this->webdriver->byId('paddle-panes-node-content-content-type-edit-form')
            );
        }

        return $this->form;
    }

    /**
     * Fills in the top section.
     *
     * @param Element $element
     *   (Optional) The parent element which contains the configuration form.
     *   For example a modal dialog or a block. This is used to build a specific
     *   xpath selector for the form elements.
     */
    public function fillInTopSection(Element $element = null)
    {
        $form = $this->topSection;

        // Set the top section status. By default this is enabled.
        if ($this->top->enable === false) {
            return;
        } else {
            $form->enable->check();
        }

        // Fill in the content type. Choose a random type if none has been set.
        $topSectionContentType = $this->topSectionContentType = $this->topSectionContentType ?: $this->getRandomTopSectionContentType();
        $form->contentTypeRadios->$topSectionContentType->select();

        // Only when text or image is selected, we should fill out the text text field.
        if (in_array($topSectionContentType, array('text', 'image'))) {
            // Fill in the text. Default to some random text.
            $text = $this->random->name(32);
            $form->text->fill($text);
        }

        // Fill in the image if the 'image' option was chosen. Default to a
        // random image.
        if ($topSectionContentType == 'image') {
            // If an image already exists, remove it before continuing.
            $image = dirname(__FILE__) . '/../../../assets/budapest.jpg';
            $alt_text = $this->random->name(12);
            $form->selectNewImage($image, $alt_text);
        }

        // Set the link type. Choose a random one if none has been set.
        $this->topSectionUrlType = $this->topSectionUrlType ?: $this->getRandomTopSectionUrlType();
        $form->urlTypeRadios->select($this->topSectionUrlType);
        // Fill in the relevant link.
        switch ($this->topSectionUrlType) {
            case 'internal':
                $this->topSectionInternalUrl = $this->topSectionInternalUrl ?: $this->getRandomUrl();
                $form->internalUrl->fill($this->topSectionInternalUrl);
                break;
            case 'external':
                $this->topSectionExternalUrl = $this->topSectionExternalUrl ?: $this->getRandomUrl();
                $form->externalUrl->fill($this->topSectionInternalUrl);
                break;
        }
    }

    /**
     * Fills in the bottom section.
     *
     * @deprecated This should be fully implemented using a Form class so we
     *   can write $this->form->bottomSectionContentType->set('X'); instead of
     *   all this webdriver and XPath nonsense.
     *
     * @param Element $element
     *   (Optional) The parent element which contains the configuration form.
     *   For example a modal dialog or a block. This is used to build a specific
     *   xpath selector for the form elements.
     */
    public function fillInBottomSection(Element $element = null)
    {
        $xpath_selector = !empty($element) ? $element->getXPathSelector() : '';

        // Set the bottom section status. By default this is enabled.
        $this->bottomSectionEnable = $this->bottomSectionEnable !== null ? $this->bottomSectionEnable : true;
        $xpath = $xpath_selector . '//input[@id="edit-bottom-enable-section"]';
        $this->moveToAndSetCheckboxValue($xpath, $this->bottomSectionEnable);

        // If the section is disabled we're done here.
        if (!$this->bottomSectionEnable) {
            return;
        }

        // Set the link type. Choose a random one if none has been set.
        $this->bottomSectionUrlType = $this->bottomSectionUrlType ?: $this->getRandomBottomSectionUrlType();
        $xpath = $xpath_selector . '//input[@id="edit-bottom-section-wrapper-section-url-type-' .
            $this->bottomSectionUrlType . '"]';
        $this->moveToAndClick($xpath);

        // Fill in the text. Default to some random text. Skip this if the link
        // type is set to 'read more link'.
        if ($this->bottomSectionUrlType != 'more-link') {
            $this->bottomSectionText = $this->bottomSectionText ?: $this->random->name(8);
            $xpath = $xpath_selector . '//input[@id="edit-bottom-section-wrapper-section-text"]';
            $this->moveToAndSetValue($xpath, $this->bottomSectionText, true);
        }

        // Fill in the relevant link.
        switch ($this->bottomSectionUrlType) {
            case 'internal':
                $this->bottomSectionInternalUrl = $this->bottomSectionInternalUrl ?: $this->getRandomUrl();
                $xpath = $xpath_selector . '//input[@id="edit-bottom-section-wrapper-section-internal-url"]';
                $this->moveToAndSetValue($xpath, $this->bottomSectionInternalUrl, true);
                break;
            case 'external':
                $this->bottomSectionExternalUrl = $this->bottomSectionExternalUrl ?: $this->getRandomUrl();
                $xpath = $xpath_selector . '//input[@id="edit-bottom-section-wrapper-section-external-url"]';
                $this->moveToAndSetValue($xpath, $this->bottomSectionExternalUrl, true);
                break;
        }
    }

    /**
     * Returns a random top section content type.
     *
     * @return string
     *   Either 'text' or 'image'.
     */
    protected function getRandomTopSectionContentType()
    {
        $types = array('title', 'text', 'image');
        $key = array_rand($types);

        return $types[$key];
    }

    /**
     * Returns a random link type.
     *
     * @return string
     *   Either 'no_link', 'internal', 'external' or 'node_link'.
     */
    protected function getRandomTopSectionUrlType()
    {
        $types = array('no_link', 'internal', 'external', 'node_link');
        $key = array_rand($types);

        return $types[$key];
    }

    /**
     * Returns a random link type.
     *
     * @return string
     *   Either 'no_link', 'internal', 'external' or 'more_link'.
     */
    protected function getRandomBottomSectionUrlType()
    {
        $types = array('no_link', 'internal', 'external', 'more_link');
        $key = array_rand($types);

        return $types[$key];
    }
}
