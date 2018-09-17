<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\CommentManagerPage\CommentManagerPageTableRow.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\CommentManagerPage;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Represents a table row on the Comment Manager Page.
 *
 * @property Checkbox $bulkActionCheckbox
 *   The checkbox to select the row for bulk actions.
 * @property string $cid
 *   The id of the comment.
 * @property string $author
 *   The author of the comment.
 * @property string $nodeTitle
 *   The title of the node to which the comment is attached.
 * @property string $lastModified
 *   The date changed of the comment.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $viewLink
 *   The link to view a comment.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $editLink
 *   The link to edit a comment.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $deleteLink
 *   The link to delete a comment.
 */
class CommentManagerPageTableRow extends Row
{

    /**
     * Constructs an CommentManagerPageTableRow object.
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
    }

    /**
     * Magical getter providing all the properties of the web element.
     *
     * @param string $property
     *   The name of the property we need.
     *
     * @throws \Exception
     *
     * @return mixed
     *   The property found.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'bulkActionCheckbox':
                $xpath = $this->xpathSelector . '//input[contains(@class, "vbo-select")]';
                $criteria = $this->webdriver->using('xpath')->value($xpath);
                $elements = $this->webdriver->elements($criteria);
                if (count($elements) > 0) {
                    return new Checkbox($this->webdriver, $elements[0]);
                }
                return false;
            case 'cid':
                $xpath = '//td[contains(@class, "views-field-cid")]';
                $element = $this->webdriver->byXPath($this->xpathSelector . $xpath);
                return trim($element->text());
            case 'author':
                $xpath = '//td[contains(@class, "views-field-name")]';
                $element = $this->webdriver->byXPath($this->xpathSelector . $xpath);
                return trim($element->text());
            case 'nodeTitle':
                $xpath = '//td[contains(@class, "views-field-title")]/a/span';
                $element = $this->webdriver->byXPath($this->xpathSelector . $xpath);
                return trim($element->text());
            case 'lastModified':
                $xpath = '//td[contains(@class, "views-field-changed")]';
                $element = $this->webdriver->byXPath($this->xpathSelector . $xpath);
                return trim($element->text());
            case 'viewLink':
                return $this->webdriver->byXPath($this->xpathSelector . '//a[contains(@class, "view-comment")]');
            case 'editLink':
                return $this->webdriver->byXPath($this->xpathSelector . '//a[contains(@class, "edit-comment")]');
            case 'deleteLink':
                return $this->webdriver->byXPath($this->xpathSelector . '//a[contains(@class, "delete-comment")]');
        }

        throw new \Exception("The property $property is undefined.");
    }

    /**
     * Checks if a given row contains the correct status for the comment.
     *
     * @param string $status
     *   The status that should be shown in the row.
     *
     * @return bool
     *   True if the row contains the given status, false otherwise.
     */
    public function checkStatus($status)
    {
        $xpath = '//td[contains(@class, "views-field-status") and contains(concat(" ", text(), " "), " '
            . $status . ' ")]/..';
        $criteria = $this->webdriver->using('xpath')->value($this->xpathSelector . $xpath);
        $elements = $this->webdriver->elements($criteria);
        return (bool) count($elements);
    }
}
