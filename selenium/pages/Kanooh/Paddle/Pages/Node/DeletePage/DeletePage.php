<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\DeletePage\DeletePage.
 */

namespace Kanooh\Paddle\Pages\Node\DeletePage;

use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The Node Delete page class.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonConfirm
 *   The "Confirm" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonCancel
 *   The "Cancel" button.
 */
class DeletePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'node/%/delete';

    /**
     * The xpath of the "Confirm" button on the page.
     * @todo - replace with the appropriate object when we have it - like "Button" or "FormElement".
     * @var string $buttonConfirmXPath
     */
    protected $buttonConfirmXPath = '//form[@id="node-delete-confirm"]//input[@id="edit-submit"]';

    /**
     * The xpath of the "Cancel" link on the page.
     * @todo - replace with the appropriate object when we have it - like "ActionLink".
     * @var string $buttonCancelXPath
     */
    protected $buttonCancelXPath = '//form[@id="node-delete-confirm"]//a[@id="edit-cancel"]';

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver);
    }

    /**
     * Magically provides all known buttons and links as properties.
     *
     * Properties that start with 'button' (links are also considered buttons), followed by the machine name of a
     * button. For example: $this->buttonCancel.
     *
     * @param string $name
     *   A button machine name prepending with 'button'.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The matching button element object.
     */
    public function __get($name)
    {
        if (isset($this->{$name . 'XPath'})) {
            return $this->webdriver->element($this->webdriver->using('xpath')->value($this->{$name . 'XPath'}));
        }

        trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name, E_USER_NOTICE);
    }


    /**
     * Finds the link to the references page if there is a message.
     *
     * @param int $nid
     *   The id of the node which is being deleted.
     *
     * @return null|\PHPUnit_Extensions_Selenium2TestCase_Element
     *   The link if present, null otherwise.
     */
    public function getReferencesLink($nid)
    {
        $link = url('node/' . $nid . '/references');
        try {
            return $this->webdriver->byXPath('//a[contains(@href, "' . $link . '")]');
        } catch (\Exception $e) {
            return null;
        }
    }
}
