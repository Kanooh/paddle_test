<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Wysiwyg\TablePropertiesModal.
 */

namespace Kanooh\Paddle\Pages\Element\Wysiwyg;

use Kanooh\Paddle\Pages\Element\Modal\ModalFormElementNotDefinedException;
use Kanooh\Paddle\Pages\Element\Wysiwyg\Table\TablePropertiesModalInfoForm;
use Kanooh\Paddle\Pages\Element\Wysiwyg\Table\TablePropertiesModalLinks;
use Kanooh\Paddle\Pages\Element\Wysiwyg\Table\TablePropertiesModalAdvancedForm;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing the modal dialog for table properties.
 *
 * @property TablePropertiesModalAdvancedForm $advancedForm
 *   The form in the 'Advanced' tab.
 * @property TablePropertiesModalInfoForm $tablePropertiesForm
 *   The form in the 'Table Info' tab.
 * @property TablePropertiesModalLinks $tabs
 *   The tabs at the top of the modal.
 */
class TablePropertiesModal extends CKEditorModal
{
    /**
     * Constructs a TablePropertiesModal object.
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
        parent::__construct($webdriver, $editor_id, 'Table Properties');
    }

    /**
     * Magic property getter.
     *
     * @param string $name
     *   A element machine name.
     *
     * @return mixed
     *   The property which has been required.
     */
    public function __get($name)
    {
        $modal_element = $this->webdriver->byXPath($this->getXPathSelector());
        switch ($name) {
            case 'advancedForm':
                $xpath = $this->getTabXPathSelector(TablePropertiesModalAdvancedForm::TABNAME);
                return new TablePropertiesModalAdvancedForm($this->webdriver, $modal_element->byXPath($xpath));
            case 'tablePropertiesForm':
                $xpath = $this->getTabXPathSelector(TablePropertiesModalInfoForm::TABNAME);
                return new TablePropertiesModalInfoForm($this->webdriver, $modal_element->byXPath($xpath));
            case 'tabs':
                return new TablePropertiesModalLinks($this->webdriver, $this->xpathSelector);
            default:
                throw new ModalFormElementNotDefinedException($name);
        }
    }
}
