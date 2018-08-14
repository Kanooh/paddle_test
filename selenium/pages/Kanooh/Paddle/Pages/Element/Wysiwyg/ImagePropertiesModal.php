<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Wysiwyg\ImagePropertiesModal.
 */

namespace Kanooh\Paddle\Pages\Element\Wysiwyg;

use Kanooh\Paddle\Pages\Element\Modal\ModalFormElementNotDefinedException;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing the modal dialog for image properties.
 *
 * @property ImagePropertiesModalAdvancedForm $advancedForm
 *   The form in the 'Advanced' tab.
 * @property ImagePropertiesModalImageInfoForm $imageInfoForm
 *   The form in the 'Image Info' tab.
 * @property ImagePropertiesModalLinks $tabs
 *   The tabs at the top of the modal.
 */
class ImagePropertiesModal extends CKEditorModal
{
    protected $submitButtonXPathSelector = '//a[@class="cke_dialog_ui_button cke_dialog_ui_button_ok"]';

    /**
     * Constructs a ImagePropertiesModal object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param string $editor_id
     *   The editor id. This usually matches the field name (for example
     *   'edit-body-und-0-value'). You can also inspect the CKEDITOR.instances
     *   variable in the javascript console to find it.
     */
    public function __construct(WebDriverTestCase $webdriver, $editor_id)
    {
        parent::__construct($webdriver, $editor_id, 'Image Properties');
    }

    /**
     * Magic getter.
     */
    public function __get($name)
    {
        $modal_element = $this->webdriver->byXPath($this->getXPathSelector());
        switch ($name) {
            case 'advancedForm':
                $xpath = $this->getTabXPathSelector(ImagePropertiesModalAdvancedForm::TABNAME);
                return new ImagePropertiesModalAdvancedForm($this->webdriver, $modal_element->byXPath($xpath));
            case 'imageInfoForm':
                $xpath = $this->getTabXPathSelector(ImagePropertiesModalImageInfoForm::TABNAME);
                return new ImagePropertiesModalImageInfoForm($this->webdriver, $modal_element->byXPath($xpath));
            case 'tabs':
                return new ImagePropertiesModalLinks($this->webdriver, $this->xpathSelector);
            default:
                throw new ModalFormElementNotDefinedException($name);
        }
    }
}
