<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Wysiwyg\LinkModalLinkInfoForm.
 */

namespace Kanooh\Paddle\Pages\Element\Wysiwyg;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Form\TextArea;

/**
 * Class representing the Link Modal Link Info Tab form.
 *
 * @property Select $linkType
 *   The form element representing the Link Type select box.
 * @property Text $link
 *   The form element representing the Link text field.
 * @property Select $protocol
 *   The form element representing the Protocol select box.
 * @property Text $url
 *   The form element representing the URL text field.
 * @property Select $byAnchorName
 *   The form element representing the By Anchor Name select box.
 * @property Select $byElementId
 *   The form element representing the By Element Id select box.
 * @property Text $emailAddress
 *   The form element representing the Email Address text field.
 * @property Text $messageSubject
 *   The form element representing the Message Subject text field.
 * @property TextArea $messageBody
 *   The form element representing the Message Body text field.
 */
class LinkModalLinkInfoForm extends Form
{
    // The 'Link Info' tab is the first visible tab. XPath counts from 1.
    const TABNUMBER = 1;

    // The content of the form element labels.
    const LINKTYPELABEL = 'Link Type';
    const LINKLABEL = 'Link';
    const PROTOCOLLABEL = 'Protocol';
    const URLLABEL = 'URL';
    const BYANCHORNAMELABEL = 'By Anchor Name';
    const BYELEMENTIDLABEL = 'By Element Id';
    const EMAILADDRESSLABEL = 'E-Mail Address';
    const MESSAGESUBJECTLABEL = 'Message Subject';
    const MESSAGEBODYLABEL = 'Message Body';

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
        $tab_xpath = '(.//div[@role = "tabpanel" and @aria-hidden = "false"])[' . self::TABNUMBER . ']';
        switch ($name) {
            case 'linkType':
                $id = $this->element->byXPath($tab_xpath . '//label[.="' . self::LINKTYPELABEL . '"]')->attribute('for');
                return new Select($this->webdriver, $this->element->byId($id));
            case 'link':
                $id = $this->element->byXPath($tab_xpath . '//label[.="' . self::LINKLABEL . '"]')->attribute('for');
                return new Text($this->webdriver, $this->webdriver->byId($id));
            case 'protocol':
                $id = $this->element->byXPath($tab_xpath . '//label[.="' . self::PROTOCOLLABEL . '"]')->attribute('for');
                return new Select($this->webdriver, $this->webdriver->byId($id));
            case 'url':
                $id = $this->element->byXPath($tab_xpath . '//label[.="' . self::URLLABEL . '"]')->attribute('for');
                return new Text($this->webdriver, $this->webdriver->byId($id));
            case 'byAnchorName':
                $id = $this->element->byXPath($tab_xpath . '//label[.="' . self::BYANCHORNAMELABEL . '"]')->attribute('for');
                return new Select($this->webdriver, $this->webdriver->byId($id));
            case 'byElementId':
                $id = $this->element->byXPath($tab_xpath . '//label[.="' . self::BYELEMENTIDLABEL . '"]')->attribute('for');
                return new Select($this->webdriver, $this->webdriver->byId($id));
            case 'emailAddress':
                $id = $this->element->byXPath($tab_xpath . '//label[.="' . self::EMAILADDRESSLABEL . '"]')->attribute('for');
                return new Text($this->webdriver, $this->webdriver->byId($id));
            case 'messageSubject':
                $id = $this->element->byXPath($tab_xpath . '//label[.="' . self::MESSAGESUBJECTLABEL . '"]')->attribute('for');
                return new Text($this->webdriver, $this->webdriver->byId($id));
            case 'messageBody':
                $id = $this->element->byXPath($tab_xpath . '//label[.="' . self::MESSAGEBODYLABEL . '"]')->attribute('for');
                return new TextArea($this->webdriver, $this->webdriver->byId($id));
        }
        throw new FormFieldNotDefinedException($name);
    }
}
