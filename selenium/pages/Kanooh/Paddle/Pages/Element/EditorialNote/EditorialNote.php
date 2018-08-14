<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\EditorialNote\EditorialNote.
 */

namespace Kanooh\Paddle\Pages\Element\EditorialNote;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class EditorialNote
 *
 * @property string $body
 *   The text of the note.
 * @property string $date
 *   The date the note was created.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element|null $linkDelete
 *   The note's delete link, null if it doesn't exist.
 * @property int $mid
 *   The note ID.
 * @property string $username
 *   The name of the note's creator.
 */
class EditorialNote extends Element
{
    /**
     * The webdriver element of the editorial note.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new EditorialNote.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The webdriver element of the editorial note.
     */
    public function __construct(WebDriverTestCase $webdriver, $element)
    {
        parent::__construct($webdriver);
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'body':
                return $this->element->byXPath('.//div[contains(@class, "message-paddle-editorial-note-content")]')->text();
            case 'date':
                return $this->element->byXPath('.//div[contains(@class, "editorial-note-date")]')->text();
            case 'linkDelete':
                $xpath = './/a[contains(@class, "delete-editorial-note")]';
                $elements = $this->element->elements($this->element->using('xpath')->value($xpath));
                return count($elements) ? $elements[0] : null;
            case 'username':
                return $this->element->byXPath('.//*[contains(@class, "username")]')->text();
            case 'mid':
                return $this->element->attribute('data-editorial-note-id');
        }

        throw new \Exception("The property $name is undefined.");
    }
}
