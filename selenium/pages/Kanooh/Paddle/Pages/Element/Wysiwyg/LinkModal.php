<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Wysiwyg\LinkModal.
 */

namespace Kanooh\Paddle\Pages\Element\Wysiwyg;

use Kanooh\Paddle\Pages\Element\Modal\ModalFormElementNotDefinedException;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing the modal dialog for adding links.
 *
 * @property LinkModalLinks $tabs
 *   The tabs at the top of the modal.
 * @property LinkModalLinkInfoForm $linkInfoForm
 *   The form in the 'Link Info' tab.
 */
class LinkModal extends CKEditorModal
{
    /**
     * Constructs a LinkModal object.
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
        parent::__construct($webdriver, $editor_id, 'Link');
    }

    /**
     * Magic getter.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'tabs':
                return new LinkModalLinks($this->webdriver, $this->xpathSelector);
            case 'linkInfoForm':
                return new LinkModalLinkInfoForm($this->webdriver, $this->webdriver->byXPath($this->xpathSelector));
            default:
                throw new ModalFormElementNotDefinedException($name);
        }
    }
}
