<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Wysiwyg\ImagePropertiesModalImageInfoForm.
 */

namespace Kanooh\Paddle\Pages\Element\Wysiwyg;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the Image Properties Modal Image Info Tab form.
 *
 * @property Text $url
 *   The form element representing the URL text field.
 * @property Text $alternativeText
 *   The form element representing the Alternative Text text field.
 * @property Text $width
 *   The form element representing the Width text field.
 * @property Text $height
 *   The form element representing the Height text field.
 * @property Checkbox $useLightbox
 *   The form element representing the "Use Lightbox" checkbox.
 * @property Select $imageStyle
 *   The form element representing the image style select.
 */
class ImagePropertiesModalImageInfoForm extends Form
{
    // Name attribute of the tab's div.
    const TABNAME = 'info';

    // The content of the form element labels.
    const URLLABEL = 'URL';
    const ALTERNATIVETEXTLABEL = 'Alternative Text';
    const WIDTHLABEL = 'Width';
    const HEIGHTLABEL = 'Height';
    const USELIGHTBOX = 'Use Lightbox';
    const IMAGESTYLE = 'Image style';

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        // CKEditor uses dynamically generated numeric IDs for all its elements
        // so we have to use XPath trickery to find our elements. These will
        // probably need be be tweaked if the CKEditor configuration changes. If
        // that's what you're here for, good luck!
        //
        // The strategy that is used to find the elements is to limit the lookup
        // per tab and target the form element labels on their label text. The
        // labels contain a "for" property which contains the ID of the form
        // element.
        switch ($name) {
            case 'url':
                $id = $this->element->byXPath('.//label[.="' . self::URLLABEL . '"]')->attribute('for');
                return new Text($this->webdriver, $this->webdriver->byId($id));
            case 'alternativeText':
                $id = $this->element->byXPath('.//label[.="' . self::ALTERNATIVETEXTLABEL . '"]')->attribute('for');
                return new Text($this->webdriver, $this->webdriver->byId($id));
            case 'width':
                $id = $this->element->byXPath('.//label[.="' . self::WIDTHLABEL . '"]')->attribute('for');
                return new Text($this->webdriver, $this->webdriver->byId($id));
            case 'height':
                $id = $this->element->byXPath('.//label[.="' . self::HEIGHTLABEL . '"]')->attribute('for');
                return new Text($this->webdriver, $this->webdriver->byId($id));
            case 'useLightbox':
                $id = $this->element->byXPath('.//label[.="' . self::USELIGHTBOX . '"]')->attribute('for');
                return new Checkbox($this->webdriver, $this->webdriver->byId($id));
            case 'imageStyle':
                $id = $this->element->byXPath('.//label[.="' . self::IMAGESTYLE . '"]')->attribute('for');
                return new Select($this->webdriver, $this->webdriver->byId($id));
        }
        throw new FormFieldNotDefinedException($name);
    }

    /**
     * Select an image style in the image properties dialog.
     *
     * @param string $image_style
     *   The image style to select.
     */
    public function selectImageStyle($image_style)
    {
        // Save the current image url value to detect when it changes.
        $current_image_url = $this->url->getContent();

        // Select the style.
        $this->imageStyle->selectOptionByValue($image_style);

        // Wait until the url changes.
        $form = $this;
        $callable = new SerializableClosure(
            function () use ($form, $current_image_url) {
                if ($current_image_url != $form->url->getContent()) {
                    return true;
                }
                return null;
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }
}
