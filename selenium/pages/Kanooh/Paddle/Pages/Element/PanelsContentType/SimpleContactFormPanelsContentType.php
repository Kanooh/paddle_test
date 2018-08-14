<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\SimpleContactFormPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SimpleContact\ConfigurationForm;

/**
 * SimpleContactFormPanelsContentType class
 *
 * @property ConfigurationForm $form
 */
class SimpleContactFormPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'simple_contact_form';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Simple contact form';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Renders a simple contact form.';

    /**
     * The simple contact page node id.
     *
     * @var int
     */
    public $simpleContactPageNid;

    /**
     * {@inheritdoc}
     *
     * @todo Refactor to use the Form class.
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        $xpath_selector = !empty($element) ? $element->getXPathSelector() : '';

        // @todo Should use Form classes for this.
        $select_xpath = $xpath_selector . '//select[@id="edit-node"]';
        $select = $this->webdriver->byXPath($select_xpath);
        $select = $this->webdriver->select($select);
        $select->selectOptionByValue($this->simpleContactPageNid);

        $this->fillInSections();
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                return new ConfigurationForm($this->webdriver, $this->webdriver->byId('paddle-simple-contact-simple-contact-form-content-type-edit-form'));
        }

        return parent::__get($name);
    }
}
