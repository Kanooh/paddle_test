<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Cultuurnet\UiTDatabankSearchForm.
 */

namespace Kanooh\Paddle\Pages\Element\Cultuurnet;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;

/**
 * Search form displayed on the UiTDatabankPane.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $submitButton
 */
class UiTDatabankSearchForm extends Form
{
    /**
     * We check that the id CONTAINS the form id, because it might be
     * appended by numbers depending on the number of renders.
     */
    protected $xpathSelector = '//form[contains(@id, "culturefeed-agenda-search-block-form")]';

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'submitButton':
                return $this->element->byXPath($this->xpathSelector . '//input[contains(@id, "edit-submit")]');
                break;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
