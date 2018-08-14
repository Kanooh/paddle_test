<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Scald\AtomFieldAtom.
 */

namespace Kanooh\Paddle\Pages\Element\Scald;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * A class representing a selected atom in an atom select field.
 *
 * @property int $id
 *   The id of the selected atom.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $removeButton
 *   Button to remove the atom from the field.
 * @property string $title
 *   Title of the atom.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $previewThumbnail
 *   Image that shows a preview of the atom.
 */
class AtomFieldAtom extends Element
{
    /**
     * The webdriver element representing the selected atom.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs an AtomFieldAtom object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The webdriver element representing the selected atom.
     */
    public function __construct(WebDriverTestCase $webdriver, $element)
    {
        parent::__construct($webdriver);
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function getWebdriverElement()
    {
        return $this->element;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'id':
                return $this->getWebdriverElement()->attribute('data-atom-id');
                break;
            case 'removeButton':
                $criteria = $this->getWebdriverElement()->using('xpath')->value('.//a[contains(@class, "remove-button")]');

                return $this->getWebdriverElement()->element($criteria);
                break;
            case 'title':
                $criteria = $this->getWebdriverElement()->using('xpath')->value('.//div[contains(@class, "title")]');
                $element = $this->getWebdriverElement()->element($criteria);

                return $element->text();
                break;
            case 'previewThumbnail':
                return $this->getWebdriverElement()->byCssSelector('img');
        }

        return parent::__get($property);
    }
}
