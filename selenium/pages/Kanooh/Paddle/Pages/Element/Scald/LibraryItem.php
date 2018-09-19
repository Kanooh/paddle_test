<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Scald\LibraryModalItem.
 */

namespace Kanooh\Paddle\Pages\Element\Scald;

/**
 * An item in the Scald library modal. Represents an atom.
 *
 * @package Kanooh\Paddle\Pages\Element\Scald
 *
 * @property int $atomId
 *   Atom ID.
 * @property string $title
 *   The title of the atom.
 * @property string $type
 *   The type of the atom.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $image
 *   Image preview.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $deleteLink
 *   The link to delete an atom.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $editLink
 *   The link to edit an atom.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $insertLink
 *   The link to insert an atom.
 */
class LibraryItem
{
    /**
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The Webdriver element.
     */
    protected $element;

    public function __construct(\PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->element = $element;
    }

    public function __get($name)
    {
        switch ($name) {
            case 'atomId':
                return $this->image->attribute('data-atom-id');
            case 'title':
                $title = $this->element->byXPath('.//div[contains(@class, "meta")]/div[contains(@class, "title")]');
                return $title->text();
            case 'type':
                $classes = $this->element->byXPath('.//div[contains(@class, "meta")]')->attribute('class');
                foreach (explode(' ', $classes) as $class) {
                    if (strpos($class, 'type-') === 0) {
                        return str_replace('type-', '', $class);
                    }
                }

                return '';
            case 'image':
                return $this->element->byXPath('.//div[contains(@class, "image")]/img');
            // We don't have a unique XPath selector for each item, so we can't
            // use the Links class.
            case 'insertLink':
                return $this->element->byXPath('.//a[contains(@class, "insert-link")]');
            case 'editLink':
                return $this->element->byXPath('.//a[contains(@class, "edit-link")]');
            case 'deleteLink':
                return $this->element->byXPath('.//a[contains(@class, "delete-link")]');
        }
    }
}
