<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage\OverviewPageTableRow.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage;

use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\Paddle\Pages\Element\Modal\ConfirmModal;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Represents a table row on the Taxonomy Overview page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkEdit
 *   The "Edit term" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkDelete
 *   The "Delete term" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkShowChildTerms
 *   The link to show the child terms.
 * @property string $termId
 *   Value of the data-term-id HTML attribute.
 */
class OverviewPageTableRow extends Row
{
    /**
     * The XPath selector that identifies the edit term link.
     */
    protected $linkEditXPathSelector = '/td/a[contains(@class, "ui-icon-edit")]';

    /**
     * The XPath selector that identifies the delete term link.
     */
    protected $linkDeleteXPathSelector = '/td/a[contains(@class, "ui-icon-delete")]';

    /**
     * The XPath selector that identifies the link that opens the level with the children of the current term.
     */
    protected $linkShowChildTermsXPathSelector = '/td/a[contains(@class, "paddle-big-vocabulary-expandable")]';

    /**
     * The confirm modal popping-up to confirm term delete.
     *
     * @var ConfirmModal
     */
    public $deleteModal;

    /**
     * Constructs an OverviewPageTableRow object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $xpath_selector
     *   The XPath selector for this table row.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath_selector)
    {
        parent::__construct($webdriver);

        $this->xpathSelector = $xpath_selector;

        $this->deleteModal = new ConfirmModal($webdriver);
    }

    /**
     * Magically provides all known operation buttons as properties. This is
     * needed as some properties will be protected and thus not accessible if
     * OverviewPageTableRow is not extended.
     *
     * @param string $name
     *   Property name.
     *
     * @return \Kanooh\Paddle\Pages\Element\Element
     *   The matching button.
     *
     * @throws \Exception
     *   Thrown when the requested property is not defined.
     */
    public function __get($name)
    {
        if (isset($this->{$name . 'XPathSelector'})) {
            $xpath = $this->xpathSelector . $this->{$name . 'XPathSelector'};
            $this->webdriver->waitUntilElementIsDisplayed($xpath);
            $this->{$name} = $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
            return $this->{$name};
        }

        switch ($name) {
            case 'termId':
                return $this->getWebdriverElement()->attribute('data-term-id');
        }

        throw new \Exception('Undefined property: ' . __CLASS__ . '::$' . $name);
    }

    /**
     * Waits until the child terms of the current table row are present.
     */
    public function waitUntilChildTermsArePresent()
    {
        $child_xpath = '/../tr[contains(@class, "paddle-big-vocabulary-parent-tid-' . $this->termId . '")]';
        $this->webdriver->waitUntilElementIsPresent($this->xpathSelector . $child_xpath);
    }

    /**
     * Focus the table drag link on that row.
     */
    public function focusTableDrag()
    {
        $this->webdriver->execute(
            array(
                'script' => "document.querySelector('tr[data-term-id=\"" . $this->termId . "\"] a.tabledrag-handle').focus();",
                'args' => array(),
            )
        );
    }
}
