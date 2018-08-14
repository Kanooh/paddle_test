<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\SimpleContactForm\SimpleContactForm.
 */

namespace Kanooh\Paddle\Pages\Element\SimpleContactForm;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\WebDriver\WebDriverTestCase;

class SimpleContactForm extends Element
{
    /**
     * {@inheritdoc}
     *
     * We check that the id CONTAINS the form id, because it might be
     * appended by numbers depending on the number of renders.
     */
    protected $xpathSelector = '//form[contains(@id, "paddle-simple-contact-field-contact-form")]';

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, $nid)
    {
        parent::__construct($webdriver);

        $nodeSelector = '//div[contains(@class, "node-' . $nid . '")]';
        $this->xpathSelector = $nodeSelector . $this->xpathSelector;
    }

    /**
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function submitButton()
    {
        $xpath = $this->xpathSelector . '//input[contains(@id, "edit-submit")]';
        return $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
    }
}
