<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\WebForm2PdfPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder;

use Kanooh\Paddle\Pages\Element\Wysiwyg\Wysiwyg;
use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\Paddle\Pages\Element\Form\Checkbox;

/**
 * The 'WebForm2Pdf Page' page of the Paddle Formbuilder module.
 *
 * @property Checkbox $generatePdf
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $bodyHeader
 * @property Wysiwyg $pageBody
 * @property WebForm2PdfPageContextualToolbar $contextualToolbar
 */
class WebForm2PdfPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'node/%/webform/webform2pdf';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'generatePdf':
                return new Checkbox($this->webdriver, $this->webdriver->byId('edit-enabled'));
            case 'bodyHeader':
                return $this->webdriver->byXPath('//*[text()[contains(.,"Content of the PDF document")]]');
            case 'pageBody':
                return new Wysiwyg($this->webdriver, 'edit-p-body-value');
            case 'contextualToolbar':
                return new WebForm2PdfPageContextualToolbar($this->webdriver);
        }

        return parent::__get($property);
    }
}
